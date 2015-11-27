<?php

namespace LooopCore\FrameworkBundle\Builder;

use LooopCore\FrameworkBundle\Builder\Toolbar\ActionsProviderInterface;

/**
 * Base interface for a builder for a view UI element. 
 * A Base View is a helper class used by a controller to organize and 
 * coordinate the elements of a single web view.
 */
interface ViewBuilderInterface extends UIBuilderInterface, 
                                       ActionsProviderInterface
{
    /**
     * Get all parameters that must be passed to the template of the view
     * @return type
     */
    public function getViewParameters($prefix = 'form', $formVariableName = 'form');
}