<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides basic access to the service container, event handling and 
 * customization through configuration for an application element.
 */
abstract class AbstractUIBuilder implements UIBuilderInterface
{
    protected $guid;
    
    /**
     * @var Container
     */
    protected $serviceContainer;
    
    /**
     * @var array
     */
    protected $options;
    
    /**     
     * @var EventDispatcher
     */
    protected $dispatcher;
    
    /**     
     * @var UIBuilderConfig
     */        
    protected $config;
    
    public function __construct(Container $serviceContainer, 
                                array $options = array())   
    {
        $this->guid = uniqid();
        $this->serviceContainer = $serviceContainer;
        $this->dispatcher = new EventDispatcher();
        $this->setValidOptions($options);
        $this->registerViewEvents();
    }
    
    public function getGuid()
    {
        return $this->guid;
    }

    public function getOption($key)
    {
        return $this->options[$key];
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getName();
    
    /**
     * {@inheritDoc}
     */
    public function getConfig() 
    {
        if (null == $this->config) {
            $this->config = new UIBuilderConfig($this, 
                                                $this->getViewConfigStore(), 
                                                $this->getViewConfigStrategy());
        }        
        
        return $this->config;
    }
        
    /**
     * {@inheritDoc}
     */
    public function registerEventListener($eventName, $listener, $priority = 0) 
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);        
    }

    /**
     * {@inheritDoc}
     */
    public function registerEventSubscriber(EventSubscriberInterface $subscriber) 
    {
        $this->dispatcher->addSubscriber($subscriber);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {                        
        $resolver->setRequired(['config_id']);
        $resolver->setDefaults(['config_id' => 'builder.' . $this->getName(),
                                'config_strategy' => '']);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) 
    {
        $this->serviceContainer = $container;
    }
    
    protected function getViewConfigStore() 
    {                
        return new UIBuilderConfigStoreYml($this->serviceContainer, 
                                           $this->options['config_id']);
    }
    
    protected function getViewConfigStrategy()
    {        
        if (!isset($this->options['config_strategy']) ||
             empty($this->options['config_strategy'])) {
            throw new Exception\MissingConfigStrategyException('The required option "config_strategy" is missing.');
        }
        
        if (!$this->serviceContainer->has($this->options['config_strategy'])) {
            throw new Exception\BuilderConfigurationException("Configuration strategy service '" . 
                                                              $this->options['config_strategy'] . 
                                                              "' was not found.");
        }
        
        return $this->serviceContainer->get($this->options['config_strategy']);                                        
    }
    
    protected function registerViewEvents() 
    {
        
    }

    protected function callViewEvent($prefix, $suffix, $event) 
    {
        $handlerFunction = $prefix . ucfirst($suffix);
        if (method_exists($this, $handlerFunction)) {
            call_user_func(array($this, $handlerFunction), $event);
        }
    }
        
    private function setValidOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);        
    }
}
