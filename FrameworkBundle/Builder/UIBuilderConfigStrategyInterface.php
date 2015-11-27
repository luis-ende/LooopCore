<?php

namespace LooopCore\FrameworkBundle\Builder;

/**
 * Defines a strategy interface for the customization of the elements of 
 * a builder with a given a configuration tree.
 */
interface UIBuilderConfigStrategyInterface 
{
    /**
     * Applies the given configuration tree to a builder.
     * 
     * @param array $configData   Configuration data to be applied
     * @param UIBuilderInterface $builder   Builder to be customized
     */
    public function applyConfiguration(array $configData, 
                                       UIBuilderInterface $builder);        
}
