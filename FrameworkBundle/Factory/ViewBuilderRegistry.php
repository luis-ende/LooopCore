<?php

namespace LooopCore\FrameworkBundle\Factory;

/**
 * Registry to hold a list of currently available views for the application.
 */
class ViewBuilderRegistry 
{
    private $views;
    
    public function __construct(array $views = array())
    {
        $this->views = $views;
    }
    
    public function addViewBuilder($key, $class)
    {
        $this->views[$key] = $class;
    }
    
    public function getViewBuilderClassName($key)
    {
        if (array_key_exists($key, $this->views)) {
            return $this->views[$key];
        } else {
            throw new \Exception("View class name for the key '" . $key 
                                . "' was not found in the registry.");
        }        
    }
}