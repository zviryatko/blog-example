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
                                'controller' => 'Admin',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'Admin',
                                'action' => 'register',
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
            'index' => array(
                'label' => 'Admin',
                'route' => 'admin',
                'icon' => 'dashboard',
                'params' => array(
                    'action' => 'index'
                ),
                'pages' => array(

                ),
            ),
        ),
    ),
);