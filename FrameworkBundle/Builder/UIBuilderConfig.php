<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Provides access to a configuration tree with the appropiated options 
 * to customize the contents produced by a builder class.
 */
class UIBuilderConfig implements ConfigurationInterface
{
    /**
     * Key of the root element in the configuration tree.
     */
    const CONFIG_ROOT_KEY = 'builder';
    /**
     * Key of the root element for the items of a builder 
     * in the configuration tree.
     */
    const CONFIG_ITEMS_KEY = 'items';
    /**
     * Key of the root elemento for the attributes of an item in the 
     * configuration tree.     
     */
    const CONFIG_PROPERTIES_KEY = 'properties';
    
    /**
     * @var UIBuilderConfigStrategyInterface
     */
    protected $strategy;
    
    /**
     * @var UIBuilderConfigStoreInterface
     */
    protected $store;
    
    /**
     * @var TreeBuilder
     */
    protected $treeBuilder;
    
    /**
     * @var array
     */
    protected $configData;
    
    /**
     * @var UIBuilderInterface
     */
    protected $builder;
         
    public function __construct(UIBuilderInterface $builder,
                                UIBuilderConfigStoreInterface $store, 
                                UIBuilderConfigStrategyInterface $strategy) 
    {        
        $this->builder = $builder;
        $this->strategy = $strategy;
        $this->store = $store;        
        $this->configData = $store->loadViewConfig($this);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() 
    {               
        if (null == $this->treeBuilder) {
            $this->treeBuilder = new TreeBuilder();
            $rootNode = $this->treeBuilder->root(UIBuilderConfig::CONFIG_ROOT_KEY);
            $rootNode
                ->children()                    
                    ->arrayNode(UIBuilderConfig::CONFIG_ITEMS_KEY)                        
                        ->isRequired()
                        ->requiresAtLeastOneElement()                        
                        ->prototype('array')
                            ->children()                                             
                                ->arrayNode(UIBuilderConfig::CONFIG_PROPERTIES_KEY)
                                    ->isRequired()
                                    ->requiresAtLeastOneElement()                            
                                    ->prototype('array')
                                        ->children()                                            
                                            ->scalarNode('title')
                                                ->defaultValue('default')
                                            ->end()
                                            ->booleanNode('visible')
                                                ->defaultValue(true)
                                            ->end()
                                            ->integerNode('index')
                                                ->defaultValue(1)
                                            ->end()
                                            ->arrayNode('options')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('value')
                                                        ->end()                                                        
                                                    ->end()                                                
                                                ->end()
                                            ->end()                                            
                                            ->arrayNode('attr')                                                            
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('value')
                                                        ->end()
                                                    ->end()                                                
                                                ->end()
                                            ->end()
                                            ->arrayNode('label_attr')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('value')
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()                    
                ->end();
        }
        
        return $this->treeBuilder;
    }
    
    /**
     * Applies the configuration to a builder through the appropriate 
     * strategy object.
     */
    public function applyConfig() 
    {
        if (null != $this->configData) {
            $this->strategy->applyConfiguration($this->configData, 
                                                $this->builder);
        }        
    }
    
    /**
     * Gets the configuration data loaded through a data configuration store.
     * 
     * @return array An array with the configuration data for a builder.
     */
    public function getConfigData() 
    {
        return $this->configData;
    }                
}

