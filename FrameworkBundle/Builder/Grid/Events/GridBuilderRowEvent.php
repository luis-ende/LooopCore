<?php

namespace LooopCore\FrameworkBundle\Builder\Grid\Events;

use Symfony\Component\EventDispatcher\Event;

class GridBuilderRowEvent extends Event {
    
    public $row;
    
    public function __construct($row) 
    {
        $this->row = $row;
    }        
}