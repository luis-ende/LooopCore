<?php

namespace LooopCore\FrameworkBundle\Builder\Menu;

use LooopCore\FrameworkBundle\Builder\UIBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Menu\MenuBuilderInterface;
use LooopCore\FrameworkBundle\Builder\UIBuilderConfigStrategyInterface;
use LooopCore\FrameworkBundle\Builder\UIBuilderConfig;
use LooopCore\FrameworkBundle\Builder\Menu\MenuNode;

/**
 * Defines a concrete strategy class for the customization of the elements of 
 * a menu builder with a given a configuration tree.
 */
class MenuBuilderConfigStrategy implements UIBuilderConfigStrategyInterface 
{
    public function applyConfiguration(array $configData, 
                                       UIBuilderInterface $builder) 
    {                        
        if ($builder instanceof MenuBuilderInterface) {            
            $menuTree = $builder->getCurrentMenuTree();
            $menuName = $builder->getName();
            $menuConfigData = $configData[UIBuilderConfig::CONFIG_ITEMS_KEY][$menuName];
            $this->customizeMenu($menuTree, $menuConfigData);            
        }
    }    
    
    protected function customizeMenu(MenuNode $menuTree, array $configData)
    {
        foreach($configData[UIBuilderConfig::CONFIG_PROPERTIES_KEY] 
                                    as $key => $values) {            
            $element = $menuTree->getNodeByName($key);
            if ($element instanceof MenuNode) {
                $element->setOption('label', $values['title']);                
                $element->setOption('visible', $values['visible']);
                $element->setOption('index', $values['index']);                
            }
        }
    }
}