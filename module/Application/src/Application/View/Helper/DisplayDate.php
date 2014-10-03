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
            case 'en_US':
            case 'en_US_POSIX':
                $format = 'F jS, Y';
                break;
            case 'en_GB':
            default:
                $format = 'jS F, Y';
        }
        
        return $dateTime->format($format);
    }
    
}