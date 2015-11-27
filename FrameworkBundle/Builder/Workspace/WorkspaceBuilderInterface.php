<?php

namespace LooopCore\FrameworkBundle\Builder\Workspace;

use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;

/**
 * Manages a set of options for the main workspace and give access to the 
 * associated content
 */
interface WorkspaceBuilderInterface 
{
    /**
     * Get access to the current active in the workspace area.
     * 
     * @return ViewBuilderInterface
     */
    public function getCurrentView();
    
    /**
     * Sets the current view in the workspace area. 
     * 
     * @param ViewBuilderInterface $view
     */
    public function setCurrentView(ViewBuilderInterface $view);
}