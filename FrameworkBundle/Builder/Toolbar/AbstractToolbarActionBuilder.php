<?php

namespace LooopCore\FrameworkBundle\Builder\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\AbstractUIControlBuilder;

abstract class AbstractToolbarActionBuilder extends AbstractUIControlBuilder                                            
{    
    protected $section;
   
    public function getName()
    {
        return 'toolbar_action_builder';
    }
    
    public function getSection()
    {
        return $this->section;
    }
    
    public function setSection($section)
    {
        $this->section = $section;
    }
    
    public function getRoute() 
    {
        return $this->options['route'];
    }        
    
    public function setRoute($route)
    {
        $this->options['route'] = $route;
    }
    
    public function getRouteParams()
    {
        return $this->options['route_params'];
    }
    
    public function setRouteParams($routeParams)
    {
        $this->options['route_params'] = $routeParams;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setRequired(['route', 'title', 'section']);
        $resolver->setDefaults(array('config_strategy' => 'looopcore.toolbaraction_builder_config_strategy', 
                                     'route_params' => array(),
                                     'visible' => true, 
                                     'enabled' => true, 
                                     'section' => 'main'));        
    }
}