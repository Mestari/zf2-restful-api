<?php

namespace Api;

use Api\Model\Group;
use Api\Model\User;
use Api\Model\UserToGroup;
use Api\Model\GroupsTable;
use Api\Model\UsersTable;
use Api\Model\UsersToGroupsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\View\Model\JsonModel;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
    }

    public function onDispatchError($e)
    {
        return $this->getJsonModelError($e);
    }

    public function onRenderError($e)
    {
        return $this->getJsonModelError($e);
    }

    public function getJsonModelError($e)
    {
        $error = $e->getError();
        if (!$error) {
            return;
        }

        $exception = $e->getParam('exception');

        if ($exception) {
            $data['error'] = $exception->getMessage();
        } else if ($error == 'error-router-no-match') {
            $data['error'] = 'Resource not found';
        }

        $model = new JsonModel($data);

        $e->setResult($model);

        return $model;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Api\Model\GroupsTable' => function ($sm) {
                        $tableGateway = $sm->get('GroupsTableGateway');
                        $table = new GroupsTable($tableGateway);
                        return $table;
                    },
                'GroupsTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Group());
                        return new TableGateway('groups', $dbAdapter, null, $resultSetPrototype);
                    },
                'Api\Model\UsersTable' => function ($sm) {
                        $tableGateway = $sm->get('UsersTableGateway');
                        $table = new UsersTable($tableGateway);
                        return $table;
                    },
                'UsersTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new User());
                        return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                    },
                'Api\Model\UsersToGroupsTable' => function ($sm) {
                        $tableGateway = $sm->get('UsersToGroupsTableGateway');
                        $table = new UsersToGroupsTable($tableGateway);
                        return $table;
                    },
                'UsersToGroupsTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new UserToGroup());
                        return new TableGateway('users_to_groups', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}