<?php

namespace LooopCore\FrameworkBundle\Factory;

use LooopCore\FrameworkBundle\Builder\Workspace\WorkspaceBuilderInterface;
use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;

/**
 * Factory for the dynamic creation of view builders registered through a 
 * ViewBuilderRegistry object.
 */
class ViewBuilderFactory implements ViewBuilderFactoryInterface
{
    private $registry;
    private $workspace;
    
    public function __construct(ViewBuilderRegistry $registry, 
                                WorkspaceBuilderInterface $workspace)
    {
        $this->registry = $registry;
        $this->workspace = $workspace;
    }
    
    /**
     * Creates, activates and returns a view builder instance.
     * 
     * @param string $key
     * @param array $params
     * @return ViewBuilderInterface | an instance of a ViewBuilderInterface
     */
    public function createAndActivate($key, array $params = array())
    {
        $view = $this->create($key, $params);        
        $this->workspace->setCurrentView($view);        
        
        return $view;
    }
    
    /**
     * Creates and returns a view builder instance.
     * 
     * @param string $key
     * @param array $params
     * @return ViewBuilderInterface | an instance of a ViewBuilderInterface
     * @throws \Exception
     */
    public function create($key, array $params = array())
    {
        $className = $this->registry->getViewBuilderClassName($key);
        
        if (!class_exists($className)) {
            throw new \Exception("ViewBuilder object couldn't be create because the registered class '" 
                                . $className . "' doesn't exist.");
        } 
        
        $class = new \ReflectionClass($className);
        $viewBuilder = $class->newInstanceArgs($params);                
        $this->checkViewBuilderInterface($viewBuilder);
        
        return $viewBuilder;        
    }
        
    private function checkViewBuilderInterface($viewBuilder) 
    {
        if (!($viewBuilder instanceof ViewBuilderInterface)) {
            throw new \Exception("The ViewBuilder object must implement the " .
                                 ViewBuilderInterface::class . " interface.");
        }        
    }
}

