<?php

namespace LooopCore\FrameworkBundle\Tests\Factory;

use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;

class TestViewBuilder implements ViewBuilderInterface
{
    const TESTVIEWBUILDER_NAME = 'test_view';
    
    public function getName() 
    {
        return TestViewBuilder::TESTVIEWBUILDER_NAME;
    }

    public function getConfig()
    {
        return null;
    }

    public function getGUID()
    {
        return 'test_guid';
    }

    public function getActions()
    {
        return array();
    }

    public function registerEventListener($eventName, $listener, $priority = 0)
    {
        
    }

    public function registerEventSubscriber(\Symfony\Component\EventDispatcher\EventSubscriberInterface $subscriber)
    {
        
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        
    }

    public function getViewParameters($prefix = 'form', $formVariableName = 'form')
    {
        return [];
    }

}