<?php

namespace LooopCore\FrameworkBundle\Builder\Panel;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderEvents;

class PanelManager
{
    /**
     * @var array
     */
    protected $panels = array();
    
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {        
        $this->eventDispatcher = $eventDispatcher;
        
        $this->initPanels();
    }
    
    public function addPanel(AbstractPanelBuilder $panel)
    {        
        $this->panels[$panel->getName()] = $panel;
    }
    
    public function removePanel($name)
    {   
        unset($this->panels[$name]);
    }
    
    public function getPanel($name)
    {
        if (array_key_exists($name, $this->panels)) {
            return $this->panels[$name];
        }
        
        return null;
    }
    
    public function getPanels() 
    {
        return $this->panels;
    }            
                
    protected function initPanels() 
    {
        $this->onInitPanelManager();
    }
    
    protected function onInitPanelManager() 
    {
        $event = new InitPanelManagerEvent($this);        
        $this->eventDispatcher->dispatch(PanelBuilderEvents::PANEL_MANAGER_INIT, 
                                         $event);        
    }        
}

