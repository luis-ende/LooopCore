<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;
use LooopCore\FrameworkBundle\Form\Model\FormNodeInterface;

/**
 * Base interface of a form builder. Provides access to the tree of elements
 * of the form.
 */
interface FormBuilderInterface extends ViewBuilderInterface
{
    /**
     * Obtains the form model tree representation of the view.
     * 
     * @return FormNodeInterface / Form Model Tree.
     */
    public function getCurrentFormModel();
}