<?php

namespace LooopCore\FrameworkBundle\Builder\Toolbar\Events;

use Symfony\Component\EventDispatcher\Event;

use LooopCore\FrameworkBundle\Builder\Toolbar\ActionsCollection;

class LoadActionsEvent extends Event
{
    /*
     * @var ToolbarPanel
     */
    protected $actions;
    
    public function __construct(ActionsCollection $actions)
    {
        $this->actions = $actions;
    }
    
    public function getActions()
    {
        return $this->actions;
    }
}