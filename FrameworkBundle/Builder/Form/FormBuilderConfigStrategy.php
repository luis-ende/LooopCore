<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use LooopCore\FrameworkBundle\Builder\UIBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Form\FormBuilderInterface;
use LooopCore\FrameworkBundle\Builder\UIBuilderConfigStrategyInterface;
use LooopCore\FrameworkBundle\Builder\Form\FormNode;
use LooopCore\FrameworkBundle\Builder\UIBuilderConfig;

/**
 * Defines a concrete strategy class for the customization of the elements of 
 * a form builder with a given a configuration tree.
 */
class FormBuilderConfigStrategy implements UIBuilderConfigStrategyInterface 
{
    public function applyConfiguration(array $configData, 
                                       UIBuilderInterface $builder) 
    {        
        if ($builder instanceof FormBuilderInterface) {            
            $formModel = $builder->getCurrentFormModel()->getFormModel();
            $formName = $builder->getCurrentFormModel()->getName();            
            $formConfigData = $configData[UIBuilderConfig::CONFIG_ITEMS_KEY][$formName];
            $this->customizeForm($formModel, $formConfigData);            
        }
    }    
    
    protected function customizeForm(FormNode $formModel, array $configData)
    {        
        foreach($configData[UIBuilderConfig::CONFIG_PROPERTIES_KEY] 
                                                    as $key => $values) {                                                            
            $element = $formModel->getNodeByName($key);
            if ($element instanceof FormNode) {
                $element->setFormOption('label', $values['title']);                
                $element->setOption('visible', $values['visible']);
                $element->setOption('index', $values['index']);                
                $this->customizeOptions($values, $element);        
                $this->customizeSuboptions('label_attr', $values, $element);
                $this->customizeSuboptions('attr', $values, $element);                                                
            }
        }
    }
    
    protected function customizeOptions(array $values, $element)
    {
        if (array_key_exists('options', $values)) {
            foreach($values['options'] as $key => $option) {
                $element->setFormOption($key, $option['value']);
            }
        }                                
    }
    
    protected function customizeSuboptions($optionKey, array $values, $element) 
    {        
        if (array_key_exists($optionKey, $values)) {
            $optionElements = $element->hasFormOption($optionKey) ?             
                                 $element->getFormOption($optionKey) : array();
            foreach($values[$optionKey] as $key => $option) {                
                $optionElements[$key] = $option['value'];
            }                    
            $element->setFormOption($optionKey, $optionElements);                
        }            
    }            
}