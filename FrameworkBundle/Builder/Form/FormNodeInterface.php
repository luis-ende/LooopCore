<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use LooopCore\FrameworkBundle\Builder\Form\FormNode;

/**
 * Interface for a form's tree representation of elements needed by a form builder
 * to customize the fields of a form.
 */
interface FormNodeInterface
{
    /**
     * Returns the name of the handled form.
     */
    public function getName();
    
    /**
     * Returns an object model of the form as tree of elements.
     */
    public function getFormModel();
    
    /**
     * Sets an a tree of elements as object model for the form.
     *
     * @param FormNode $formModel
     */
    public function setFormModel(FormNode $formModel);               
}