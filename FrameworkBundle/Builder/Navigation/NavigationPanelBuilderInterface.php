<?php

namespace LooopCore\FrameworkBundle\Builder\Navigation;

use LooopCore\FrameworkBundle\Builder\Toolbar\ActionsProviderInterface;

interface NavigationPanelBuilderInterface extends ActionsProviderInterface                                          
{    
    public function setNavigationProvider(NavigationStructureProviderInterface $provider);                
    
    public function getNavigationTree();
    
    /**
     * Get the current active element in the navigation structure.
     * 
     * @return NavigationElementInterface | instance of NavigationPanelBuilderInterface
     */
    public function getCurrentNavigationElement();
    
    public function setCurrentNavigationElement($id);
}