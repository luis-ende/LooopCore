<?php

namespace LooopCore\FrameworkBundle\Builder\Grid\Events;

use Symfony\Component\EventDispatcher\Event;

class GridBuilderColumnEvent extends Event {
    
    public $column;
    public $cellValue;
    public $row;
    public $router;
    
    public function __construct($column, $cellValue, $row, $router) 
    {
        $this->column = $column;
        $this->cellValue = $cellValue;
        $this->row = $row;
        $this->router = $router;
    }        
}