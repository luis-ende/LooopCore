<?php

namespace LooopCore\FrameworkBundle\Form\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class FormBuilderEmptyDataEvent extends Event
{
    /**
     *
     * @var FormInterface
     */
    private $form;
    
    private $entity;
    
    private $formId;
    
    public function __construct(FormInterface $form, $formId)
    {
        $this->form = $form;        
        $this->formId = $formId;
    }
    
    public function getForm()
    {
        return $this->form;
    }      
    
    public function getDataClass() 
    {
        return $this->form->getConfig()->getDataClass();
    }
    
    public function setData($entity) 
    {
        $this->entity = $entity;
    }
    
    public function getData()
    {
        return $this->entity;
    }

    public function getFormId() {
        return $this->formId;
    }
}
