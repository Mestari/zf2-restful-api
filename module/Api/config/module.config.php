<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Group' => 'Api\Controller\GroupController',
            'Api\Controller\User' => 'Api\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'group' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/group[/:action][/:group_id][/:user_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'group_id' => '[0-9]+',
                        'user_id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Group',
                        'action' => 'index',
                    ),
                ),
            ),
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:action][/:user_id][/:group_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'user_id' => '[0-9]+',
                        'group_id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\User',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5'
    ),
);