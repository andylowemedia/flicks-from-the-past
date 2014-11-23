<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoogleAnalytics extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)  
    {  
        $this->serviceLocator = $serviceLocator;  
        return $this;  
    }
    
    public function getServiceLocator()  
    {  
        return $this->serviceLocator;  
    }  
    public function __invoke()
    {
        $config = $this->getServiceLocator()->getServiceLocator()->get('config');
        
        $useGoogleAnalytics = true;
        
        if (isset($config['googleAnalytics'])) {
            if ($config['googleAnalytics'] === 'hide' || $config['googleAnalytics'] !== 'show') {
                $useGoogleAnalytics = false;
            }
        }
        
        return $useGoogleAnalytics;
    }
    
}