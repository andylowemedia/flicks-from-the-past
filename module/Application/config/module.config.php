<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'articles' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/articles',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articles',
                        'action'     => 'index',
                    ),
                ),
            ),
            'article' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/article',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articles',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'profile' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:slug]',
                            'defaults' => array(
                                'action'     => 'article',
                            ),
                            'constraints' => array(
                                'slug' => '[0-9a-zA-Z-]+'
                            ),
                        ),
                    ),
                ),

            ),
            'amazon' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/amazon',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Amazon',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'products   ' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/products',
                            'defaults' => array(
                                'action'     => 'productApi',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
                'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Articles' => 'Application\Controller\ArticlesController',
            'Application\Controller\Amazon' => 'Application\Controller\AmazonController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
