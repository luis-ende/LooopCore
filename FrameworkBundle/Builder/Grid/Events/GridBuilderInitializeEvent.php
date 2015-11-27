<?php

namespace LooopCore\FrameworkBundle\Builder\Grid\Events;

use Symfony\Component\EventDispatcher\Event;

use LooopCore\FrameworkBundle\Builder\Grid\GridBuilderInterface;

class GridBuilderInitializeEvent extends Event {
    
    public $gridView;
    
    public function __construct(GridBuilderInterface $gridView) 
    {
        $this->gridView = $gridView;
    }  
    
    public function getGridView() {
        return $this->gridView;
    }
}