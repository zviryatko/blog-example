<?php
return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view' => array(
                        'type' => 'entity',
                        'options' => array(
                            'route' => '/view/:id',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'view',
                                'entity' => 'User\Entity\User',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'password' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/password',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action' => 'password',
                            ),
                        ),
                    ),
                ),
            ),
            'admin' => array(
                'child_routes' => array(
                    'user' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Admin',
                                'action' => 'index',
                                'headTitle' => 'Users',
                                'pageTitle' => 'Users',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'index' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/index',
                                ),
                            ),
                            'add' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/add',
                                ),
                            ),
                            'edit' => array(
                                'type' => 'entity',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'constraints' => array(
                                        'id' => '[0-9]+'
                                    ),
                                    'defaults' => array(
                                        'action' => 'edit',
                                        'entity' => 'User\Entity\User',
                                        'headTitle' => 'Edit profile',
                                        'pageTitle' => 'Edit profile',
                                    ),
                                ),
                            ),
                            'view' => array(
                                'type' => 'entity',
                                'options' => array(
                                    'route' => '/view/:id',
                                    'constraints' => array(
                                        'id' => '[0-9]+'
                                    ),
                                    'defaults' => array(
                                        'action' => 'edit',
                                        'entity' => 'User\Entity\User',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
        'controller_map' => array(
            'User\Controller\AuthController' => 'user',
            'User\Controller\IndexController' => 'user',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'username' => 'User\View\Helper\Username',
            'userpicture' => 'User\View\Helper\UserPicture',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
        ),
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'doctrine.authenticationservice.default',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'User\Controller\Admin' => 'User\Controller\AdminControllerFactory',
            'User\Controller\Index' => 'User\Controller\IndexControllerFactory',
            'User\Controller\Auth' => 'User\Controller\AuthControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'DoctrineModule\Stdlib\Hydrator\DoctrineObject' => 'DoctrineORMModule\Service\DoctrineObjectHydratorFactory'
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'User\Form\LoginForm' => 'User\Form\LoginFormFactory',
            'User\Form\RegisterForm' => 'User\Form\RegisterFormFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'user_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'user_entities'
                ),
            ),
        ),
        'authenticationadapter' => array('default' => true),
        'authenticationservice' => array('default' => true),
        'authenticationstorage' => array('default' => true),
        'authentication' => array(
            'default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => 'User\Service\UserService::verifyHashedPassword',
            ),
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'user' => array(
                'label' => 'Users',
                'route' => 'admin/user',
                'icon' => 'users',
                'pages' => array(
                    'index' => array(
                        'label' => 'List',
                        'route' => 'admin/user',
                        'icon' => 'th-list',
                    ),
                    'new' => array(
                        'label' => 'Add new',
                        'route' => 'admin/user/add',
                        'icon' => 'user-plus',
                    ),
                ),
            ),
        ),
        'user' => array(
            array(
                'label' => 'Login',
                'route' => 'user/login',
            ),
            array(
                'label' => 'Registration',
                'route' => 'user/register',
            ),
            array(
                'label' => 'Logout',
                'route' => 'user/logout',
            ),
        ),
    ),
);