<?php

namespace LooopCore\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class RegisterViewBuildersPass implements CompilerPassInterface
{    
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('looopcore.viewbuilder_registry')) {
            throw new Exception("ViewBuilderRegistry service has not been registered in the service container.");
        }
        $viewBuilderRegistry = $container->getDefinition('looopcore.viewbuilder_registry');        
        $viewBuilderDefs = $this->loadViewBuilderDefs($container);                  
        foreach ($viewBuilderDefs as $key => $value) {            
            $viewBuilderRegistry->addMethodCall('addViewBuilder', 
                                               array($key, $value));
        }
    }           
        
    private function loadViewBuilderDefs(ContainerBuilder $container) 
    {        
        $viewBuilderDefs = array();
        $params = $container->getParameterBag()->all();
        foreach($params as $key => $configSection) {            
            if ($this->isALooopParameterSection($key)) {                
                $processor = new Processor();
                $configTree = $this->getConfigTreeBuilder();
                $configSection = $processor->process($configTree->buildTree(), 
                                              $configSection);                                
                $sectionDefs = $this->getViewBuilderDefs($configSection['view_builders']);                
                $viewBuilderDefs = array_merge($viewBuilderDefs, $sectionDefs);
            }            
        }
        
        return $viewBuilderDefs;
    }  
    
    private function isALooopParameterSection($key) 
    {
        return substr_compare($key, 'looopapp_', 0, strlen('looopapp_')) == 0;
    }
    
    private function getViewBuilderDefs(array $viewBuilders) 
    {
        $defs = array();
        foreach ($viewBuilders as $key => $value) {
            $defs[$key] = $value['class'];
        }
        
        return $defs;
    }
        
    protected function getConfigTreeBuilder() 
    {        
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('view_definitions');
        $rootNode
                ->children()
                    ->arrayNode('view_builders')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('class')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
                
        return $treeBuilder;
    }
}