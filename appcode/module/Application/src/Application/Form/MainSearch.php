<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class MainSearch extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->init();
    }
    
    public function init()
    {
        $searchText = new Element\Text('search');
        $this->add($searchText);
        
        $type = new Element\Select('type');
        $type->setValueOptions(array('all' => 'All','features' => 'Features', 'reviews' => 'Reviews'));
        $this->add($type);
        
        $searchButton = new Element\Submit('submit');
        $searchButton->setValue('Search');
        $this->add($searchButton);
    }
}
