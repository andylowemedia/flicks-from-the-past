<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager           = $e->getApplication()->getEventManager();
        $moduleRouteListener    = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->defineApplicationLocal($e->getApplication()->getServiceManager()->get('Config'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'DisplayDate'       => 'Application\View\Helper\DisplayDate',
                'GoogleAnalytics'   => 'Application\View\Helper\GoogleAnalytics',
                'MainSearch'        => 'Application\View\Helper\MainSearch',
                'FormSubmit'        => 'Application\View\Helper\FormSubmit',
             ),
        );
    }
    
    public function defineApplicationLocal(array $config)
    {
        if ($config) {
            \Locale::setDefault($config['locale']);
        }
    }
}
