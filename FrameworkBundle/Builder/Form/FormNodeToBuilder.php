<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use Symfony\Component\Form\FormBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Form\FormNode;

/**
 * Converts from a LLP Form Model (ForNode) representation to a Symfony 
 * representation (Builder).
 */
class FormNodeToBuilder 
{
    protected $builder;
    protected $formModel;
    
    public function __construct(FormBuilderInterface $builder, 
                                FormNode $formModel) 
    {
        $this->builder = $builder;
        $this->formModel = $formModel;
    }
    
    public function formModelToBuilder() 
    {                
        $this->formModel->sortItems();
        $this->processModel($this->formModel->getChildren(), 
                            $this->builder);        
    }
    
    protected function processModel($formModel, 
                                    FormBuilderInterface $builder) 
    {        
        foreach($formModel as $modelItem) {
            if ($modelItem instanceof FormNode) {                                
                $this->processModelItem($modelItem, $builder);
            }                         
        }
    }
    
    protected function processModelItem(FormNode $modelItem, 
                                        FormBuilderInterface $builder) 
    {
        $hasChildren = $modelItem->hasChildren();        
        
        if ($modelItem->getOptions()['visible']) {
            if ($hasChildren) {            
                $builderParent = $builder->create($modelItem->getOptions()['id'], 
                                                  $modelItem->getOptions()['type'], 
                                                  isset($modelItem->getOptions()['options']) ? 
                                                    $modelItem->getOptions()['options'] : array());
                $builder->add($builderParent);
                $modelItem->sortItems();
                $this->processModel($modelItem->getChildren(), $builderParent);            
            }                   
            else if ($modelItem->getOptions()['visible']) {
                $builder->add($modelItem->getOptions()['id'], 
                              $modelItem->getOptions()['type'], 
                              isset($modelItem->getOptions()['options']) ? 
                                $modelItem->getOptions()['options'] : array());
            }
        }
    }
}
