<?php

namespace LooopCore\FrameworkBundle\Controller\Toolbar;

use LooopCore\FrameworkBundle\Controller\BaseAction;
use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Toolbar\ToolbarPanel;

class ToolbarController extends BaseAction 
{
    public function renderPanelAction(PanelBuilderInterface $panel)
    {
        return $this->renderDefaultView(array('commands' => $this->getCommands($panel)));        
    }
    
    protected function getCommands(PanelBuilderInterface $panel)
    {                        
        if ($panel instanceof ToolbarPanel) {
            return $panel->getActions();
        }
        
        return array();
    }
}