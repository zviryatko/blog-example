<?php
return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Admin',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Auth',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[index]',
                            'defaults' => array(
                                'controller' => 'Admin',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Navigation\Admin' => 'Admin\Navigation\AdminNavigationFactory',
        ),
        'aliases' => array(
            'Admin\Navigation\AdminNavigation' => 'Zend\Navigation\Admin',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController'
        ),
    ),
    'navigation_helpers' => array(
        'invokables' => array(
            'adminBreadcrumbs' => 'Admin\View\Helper\Navigation\AdminBreadcrumbs',
            'adminSidebarMenu' => 'Admin\View\Helper\Navigation\AdminSidebarMenu',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'layout/admin' => __DIR__ . '/../view/layout/admin.phtml',
            'admin' => __DIR__ . '/../view',
        ),
        'controller_map' => array(
            'Admin\Controller\AdminController' => 'admin',
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'header' => array(
                'label' => 'Main navigation',
                'class' => 'header text-uppercase',
                'uri' => '',
            ),
            'index' => array(
                'label' => 'Admin',
                'route' => 'admin',
                'icon' => 'dashboard',
                'class' => 'admin',
            ),
        ),
    ),
);