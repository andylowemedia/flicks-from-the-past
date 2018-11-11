<?php
namespace Application\View\Helper;
use Application\Form;
use Zend\View\Helper\AbstractHelper;

class MainSearch extends AbstractHelper
{
    public function __invoke()
    {
        return new Form\MainSearch('mainSearch');
    }
    
}