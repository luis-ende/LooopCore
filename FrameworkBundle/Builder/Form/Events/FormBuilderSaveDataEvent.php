<?php

namespace LooopCore\FrameworkBundle\Builder\Form\Events;

use Symfony\Component\EventDispatcher\Event;

class FormBuilderSaveDataEvent extends Event
{        
    private $data;
    private $formId;
    
    public function __construct($data, $formId)
    {
        $this->data = $data;   
        $this->formId = $formId;
    }        
    
    public function getData()
    {
        return $this->data;
    }   
    
    public function getFormId() 
    {
        return $this->formId;
    }
}
