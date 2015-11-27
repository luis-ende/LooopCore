<?php

namespace LooopCore\FrameworkBundle\Builder\Panel;

use Symfony\Component\EventDispatcher\Event;

use LooopCore\FrameworkBundle\Builder\Panel\PanelManager;

class InitPanelManagerEvent extends Event
{
    protected $panelManager;
    
    public function __construct(PanelManager $panelManager)
    {        
        $this->panelManager = $panelManager;
    }
    
    public function getPanelManager()
    {
        return $this->panelManager;
    }
}