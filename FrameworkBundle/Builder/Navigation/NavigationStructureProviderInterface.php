<?php

namespace LooopCore\FrameworkBundle\Builder\Navigation;

interface NavigationStructureProviderInterface
{
    public function provideNavigationTree(); 
    
    /**
     * @param array | Array of NavigationElementInterface
     */
    public function refreshNavigationTree(array $navigationTree);
}