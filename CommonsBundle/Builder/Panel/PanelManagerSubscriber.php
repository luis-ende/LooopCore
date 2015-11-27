<?php

namespace LooopCore\CommonsBundle\Builder\Panel;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\Container;

use LooopCore\FrameworkBundle\Builder\Toolbar\ToolbarPanel;
use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderEvents;
use LooopCore\FrameworkBundle\Builder\Panel\InitPanelManagerEvent;

class PanelManagerSubscriber implements EventSubscriberInterface 
{
    protected $serviceContainer;
    
    public function __construct(Container $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }
    
    public static function getSubscribedEvents() 
    {
        return array(PanelBuilderEvents::PANEL_MANAGER_INIT => 'initPanelManager');
    }
    
    public function initPanelManager(InitPanelManagerEvent $event)
    {        
        $panelManager = $event->getPanelManager();                
        
//        $panelManager->addPanel(new SubviewPanel($this->serviceContainer, array()));
        $panelManager->addPanel(new ToolbarPanel($this->serviceContainer, array()));
//        $panelManager->addPanel(new FilterPanel($this->serviceContainer, array()));
//        $panelManager->addPanel(new ExportPanel($this->serviceContainer, array()));        
    }            
}

