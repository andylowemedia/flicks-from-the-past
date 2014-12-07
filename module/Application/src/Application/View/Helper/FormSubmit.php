<?php
namespace Application\View\Helper;
use Zend\Form\View\Helper\FormSubmit as ZendFormSubmit;
use Zend\Form\ElementInterface;

class FormSubmit extends ZendFormSubmit
{
    public function render(ElementInterface $element)
    {
        $attributes          = $element->getAttributes();
        if (isset($attributes['name'])) {
            unset($attributes['name']);
        }
        $attributes['type']  = $this->getType($element);
        $attributes['value'] = $element->getValue();

        return sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket()
        );
    }
}
