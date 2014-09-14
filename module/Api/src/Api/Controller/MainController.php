<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;

class MainController extends AbstractRestfulController
{
    protected $groupsTable;
    protected $usersTable;
    protected $usersToGroupsTable;

    protected $inputUserId;
    protected $inputGroupId;
    protected $jsonDecodedData;
    protected $userInfo;
    protected $groupInfo;

    public function notFoundAction()
    {
        $this->response->setStatusCode(404);
        throw new \Exception('Unknown action name');
    }

    /**
     * Get table 'groups'
     * @return array|object
     */
    public function getGroupsTable()
    {
        if (!$this->groupsTable) {
            $sm = $this->getServiceLocator();
            $this->groupsTable = $sm->get('Api\Model\GroupsTable');
        }
        return $this->groupsTable;
    }

    /**
     * Get table 'users'
     * @return array|object
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Api\Model\UsersTable');
        }
        return $this->usersTable;
    }

    /**
     * Get table 'users_to_groups'
     * @return array|object
     */
    public function getUsersToGroupsTable()
    {
        if (!$this->usersToGroupsTable) {
            $sm = $this->getServiceLocator();
            $this->usersToGroupsTable = $sm->get('Api\Model\UsersToGroupsTable');
        }
        return $this->usersToGroupsTable;
    }

    /**
     * Get input parameter group_id
     * @throws \Exception
     */
    protected function getInputGroupId()
    {
        $this->inputGroupId = (int)$this->params()->fromRoute('group_id', null);

        if (empty($this->inputGroupId)) {
            throw new \Exception("Empty group ID");
        }
    }

    /**
     * Get input parameter user_id
     * @throws \Exception
     */
    protected function getInputUserId()
    {
        $this->inputUserId = (int)$this->params()->fromRoute('user_id', null);

        if (empty($this->inputUserId)) {
            throw new \Exception("Empty user ID");
        }
    }

    /**
     * Get input JSON data
     * @throws \Exception
     */
    protected function getContentData()
    {
        $body = $this->getRequest()->getContent();

        try {
            $this->jsonDecodedData = Json::decode($body);
        } catch(\Exception $e) {
            throw new \Exception("Wrong input JSON data");
        }
    }

    /**
     * Check user access
     * @throws \Exception
     */
    protected function checkAccess()
    {
        if (empty($this->jsonDecodedData->user_id)
            || empty($this->jsonDecodedData->password)
        ) {
            throw new \Exception("Wrong input data");
        }

        $userInfo = $this->getUsersTable()->getUser($this->jsonDecodedData->user_id);
        $password = md5($this->jsonDecodedData->password);

        if ($password != $userInfo->password
            || !($userInfo->role & 2)
            || !($userInfo->role & 1)
        ) {
            throw new \Exception("Access denied");
        }
    }
}