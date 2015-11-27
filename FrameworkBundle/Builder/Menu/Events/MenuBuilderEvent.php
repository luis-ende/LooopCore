<?php

namespace LooopCore\FrameworkBundle\Builder\Menu\Events;

use Symfony\Component\EventDispatcher\Event;

use LooopCore\FrameworkBundle\Builder\Menu\MenuNode;

class MenuBuilderEvent extends Event
{
    private $menuTree;
    
    public function __construct(MenuNode $menuTree)
    {
        $this->menuTree = $menuTree;
    }
    
    public function getMenuTree()
    {
        return $this->menuTree;
    }
}

