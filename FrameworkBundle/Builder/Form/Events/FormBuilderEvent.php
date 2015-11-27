<?php

namespace LooopCore\FrameworkBundle\Builder\Form\Events;

use Symfony\Component\EventDispatcher\Event;

use LooopCore\FrameworkBundle\Builder\Form\FormNodeInterface;

class FormBuilderEvent extends Event
{
    
    /**
     *
     * @var \LooopCore\FrameworkBundle\Form\Model\FormNodeInterface
     */
    private $llpForm;    
    private $formData;
    
    public function __construct(FormNodeInterface $llpForm, $formData)
    {
        $this->llpForm = $llpForm;
        $this->formData = $formData;
    }
    
    public function getForm()
    {
        return $this->llpForm;
    }
    
    /**
     * Returns the root data entity used by the form.
     *      
     */
    public function getFormData()
    {
        return $this->formData;
    }
}