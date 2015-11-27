<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\DependencyInjection\Container;

/**
 * Obtains a configuration tree section from the application's configuration.
 */
class UIBuilderConfigStoreYml implements UIBuilderConfigStoreInterface
{    
    protected $serviceContainer;
    
    protected $configId;
    
    public function __construct(Container $serviceContainer, $configId)         
    {   
        $this->serviceContainer = $serviceContainer;
        $this->configId = $configId;
    }
        
    /**
     * {@inheritDoc}
     */
    public function loadViewConfig(ConfigurationInterface $builderConfig)    
    {
        if ($this->serviceContainer->hasParameter($this->configId)) {
            $configSection = $this->serviceContainer->getParameter($this->configId);            
            $processor = new Processor();        
            $config = $processor->processConfiguration($builderConfig, 
                                                                $configSection);
            
            return $config;
        }
        
        return null;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function saveViewConfig(ConfigurationInterface $builderConfig, 
                                   array $configData = null) 
    {        
//        if (null == $configData) {
//            $dumper = new YamlReferenceDumper();                
//            $yaml = $dumper->dump($viewConfig);        
//        }
//        else {
//            $yaml = Yaml::dump($configData);
//        }
//                
//        file_put_contents($this->fileName, $yaml);
    }    
}