<?php

namespace LooopCore\FrameworkBundle\Tests\Factory;

use LooopCore\FrameworkBundle\Builder\Workspace\WorkspaceBuilderInterface;
use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;

class TestWorkspaceBuilder implements WorkspaceBuilderInterface
{
    protected $currentView;
    
    public function getCurrentView()
    {
        return $this->currentView;
    }

    public function setCurrentView(ViewBuilderInterface $view)
    {
        $this->currentView = $view;
    }

}