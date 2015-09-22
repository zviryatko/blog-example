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
            'username' => 'User\View\Helper\UsernameHelper',
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
            'User\Controller\Index' => 'User\Controller\IndexControllerFactory',
        ),
        'invokables' => array(
            'User\Controller\Auth' => 'User\Controller\AuthController',
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