<?php

namespace LooopCore\FrameworkBundle\Builder\Toolbar;

use LooopCore\FrameworkBundle\Commons\Collection\Collection;

class ActionsCollection extends Collection
{    
    public function add($item, $key = null)
    {        
        if ($item instanceof AbstractToolbarActionBuilder) {
            parent::add($item, $item->getGuid());
        }
        else {
            throw new \Exception("Action object must be of type '" . 
                                 AbstractToolbarActionBuilder::class . "'");
        }
    }
    
    public function addActions(array $toolbarActions = array())
    {        
        foreach($toolbarActions as $action) {
            $this->add($action, $action->getGuid());            
        }                            
    }    
}