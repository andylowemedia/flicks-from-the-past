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
            'sitemap' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/sitemap',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'sitemap',
                    ),
                ),
            ),
            'search' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/search[/:search]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articles',
                        'action'     => 'search',
                    ),
                ),
            ),
            'articles' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/articles[/:type]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Articles',
                        'action'     => 'index',
                    ),
                )
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
                    'title' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/title[/:slug]',
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
            
            'youtube' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/youtube',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'youtube',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'profile' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/',
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
            'Application\Controller\Index'      => 'Application\Controller\IndexController',
            'Application\Controller\Articles'   => 'Application\Controller\ArticlesController',
            'Application\Controller\Amazon'     => 'Application\Controller\AmazonController',
        ),
    ),
   'controller_plugins' => array(
        'invokables' => array(
            'AmazonCategorySearch' => 'Application\Controller\Plugin\AmazonCategorySearch',
        )
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
        'strategies' => array(
            'ViewJsonStrategy',
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
