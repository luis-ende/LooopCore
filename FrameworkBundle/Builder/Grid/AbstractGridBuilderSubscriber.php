<?php

namespace LooopCore\FrameworkBundle\Builder\Grid;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderEvents;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderInitializeEvent;

abstract class AbstractGridBuilderSubscriber implements EventSubscriberInterface 
{
    
    public static function getSubscribedEvents() 
    {
        return array(GridBuilderEvents::GRID_BEFORE_BUILD => 'preBuildView', 
                     GridBuilderEvents::GRID_AFTER_BUILD => 'postBuildView');
    }
    
    final public function preBuildView(GridBuilderInitializeEvent $event) 
    {
        if ($this->getSubscribedViewName() == $event->gridView->getName()) {
            $this->preBuild($event);
        }
    }
    
    final public function postBuildView(GridBuilderInitializeEvent $event) 
    {        
        if ($this->getSubscribedViewName() == $event->gridView->getName()) {
            $this->postBuild($event);
        }
    }
    
    public function preBuild(GridBuilderInitializeEvent $event) 
    {        
    }

    public function postBuild(GridBuilderInitializeEvent $event) 
    {        
        $gridView = $event->getGridView();
        $viewConfig = $gridView->getConfig();
        $viewConfig->applyConfig();        
    }

    /**
     * The name of the view that should be handled
     * @return $mixed String or String[]
     */
    abstract public function getSubscribedViewName();
}