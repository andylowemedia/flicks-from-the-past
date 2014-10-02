<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

class DisplayDate extends AbstractHelper
{
    public function __invoke($date)
    {
        $dateTime = new \DateTime($date);
        $locale = \Locale::getDefault();
        
        
        switch ($locale) {
            case 'en_US_POSIX':
                $format = 'F dS, Y';
                break;
            case 'en_GB':
                $format = 'jS F, Y';
                break;
        }
        
        return $dateTime->format($format);
    }
    
}