<?php
return array(
    'router' => array(
        'routes' => array(
            'article' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/article',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Article\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'add',
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
                                'controller' => 'Index',
                                'action' => 'view',
                                'entity' => 'Article\Entity\Article',
                            ),
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
                                'controller' => 'Index',
                                'action' => 'edit',
                                'entity' => 'Article\Entity\Article',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'entity',
                        'options' => array(
                            'route' => '/delete/:id',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'delete',
                                'entity' => 'Article\Entity\Article',
                            ),
                        ),
                    ),
                ),
            ),
            'admin' => array(
                'child_routes' => array(
                    'article' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/article',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Article\Controller',
                                'controller' => 'Admin',
                                'action' => 'list',
                                'headTitle' => 'Articles',
                                'pageTitle' => 'Articles',
                                'pageDescription' => 'List of all articles',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'list' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/list',
                                ),
                            ),
                            'view' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/view',
                                    'defaults' => array(
                                        'action' => 'view',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'index' => array(
                'pages' => array(
                    'article' => array(
                        'label' => 'Articles',
                        'route' => 'admin/article',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'article' => __DIR__ . '/../view',
        ),
        'controller_map' => array(
            'Article\Controller\IndexController' => 'article',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'article_metadata' => 'Article\View\Helper\ArticleMetadataHelper',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
        ),
        'aliases' => array(
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Article\Controller\Index' => 'Article\Controller\IndexControllerFactory',
            'Article\Controller\Admin' => 'Article\Controller\AdminControllerFactory',
        ),
        'invokables' => array(
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'DoctrineModule\Stdlib\Hydrator\DoctrineObject' => 'DoctrineORMModule\Service\DoctrineObjectHydratorFactory'
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'Article\Form\AddForm' => 'Article\Form\AddFormFactory',
            'Article\Form\EditForm' => 'Article\Form\EditFormFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'article_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Article/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Article\Entity' => 'article_entities'
                ),
            ),
        ),
    ),
);