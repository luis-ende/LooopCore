<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Abstracts the persistence layer of a builder's configuration tree which 
 * could be stored and retrieved from different sources.
 */
interface UIBuilderConfigStoreInterface
{
    /**
     * Loads from a store and returns a configuration's tree structure.
     * 
     * @param ConfigurationInterface    $builderConfig  The Configuration structure
     * 
     * @return array    The configuration data
     */
    public function loadViewConfig(ConfigurationInterface $builderConfig);
    
    /**
     * Saves a configurations's tree structure in a given data store.
     * 
     * @param ConfigurationInterface    $builderConfig  The Configuration structure
     * @param array $configData Configuration data to be saved
     */
    public function saveViewConfig(ConfigurationInterface $builderConfig, 
                                   array $configData = null);
}