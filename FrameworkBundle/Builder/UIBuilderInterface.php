<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UIBuilder Interface for a helper class responsible to build an specific 
 * application element (i.e. view, form, menu). 
 */
interface UIBuilderInterface
{    
    /**
     * Obtains a unique identifier for the UI element.
     * 
     * @return string GUID / Identifier
     */
    public function getGUID();
    
    /**
     * Obtains the name / name of the view builder.
     * 
     * @return string Name / name of the view builder
     */
    public function getName();

    public function getOption($key);
    
    /**
     * Returns a properly created configuration object for the builder.
     * 
     * @return UIBuilderConfig  Builder's configuration object
     */
    public function getConfig();            
        
    /**
     * Set valid general options for a builder through a resolver object.
     * 
     * @param OptionsResolverInterface OptionsResolver object
     */
    public function setDefaultOptions(OptionsResolver $resolver);   
    
    /**
     * Adds an event listener to the dispatcher associated to the builder.
     * 
     * @param string Event's name
     * @param mixed Handling method
     * @param int Listener's priority     
     */
    public function registerEventListener($eventName, $listener, $priority = 0);
    
    /**
     * Adds a subscriber to the dispatcher associated to the builder.
     * 
     * @param EventSubscriberInterface Event's subscriber  
     */
    public function registerEventSubscriber(EventSubscriberInterface $subscriber);
    
    public function setContainer(ContainerInterface $container = null);
}