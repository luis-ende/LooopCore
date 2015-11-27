<?php

namespace LooopCore\FrameworkBundle\Builder\Grid;

use LooopCore\FrameworkBundle\Builder\UIBuilderConfigStrategyInterface;
use LooopCore\FrameworkBundle\Builder\UIBuilderInterface;
use LooopCore\FrameworkBundle\Builder\UIBuilderConfig;

use APY\DataGridBundle\Grid\Grid;

/**
 * Defines a concrete strategy class for the customization of the elements of 
 * a grid builder with a given a configuration tree.
 */
class GridBuilderConfigStrategy implements UIBuilderConfigStrategyInterface 
{
    public function applyConfiguration(array $configData, 
                                       UIBuilderInterface $builder) 
    {              
        if ($builder instanceof GridBuilderInterface) {
            $grid = $builder->getDataGrid();
            $gridConfigData = $configData[UIBuilderConfig::CONFIG_ITEMS_KEY][$builder->getName()];
            $this->customizeGrid($grid, $gridConfigData);            
        }
    }    
    
    protected function customizeGrid(Grid $grid, array $configData) {        
        foreach($configData[UIBuilderConfig::CONFIG_PROPERTIES_KEY] 
                                                as $key => $values) {
            if ($grid->hasColumn($key)) {
                $column = $grid->getColumn($key);
                $column->setTitle($values['title']);
                $column->setVisible($values['visible']);
                $column->setOrder($values['index']);
            }
        }
    }
}
