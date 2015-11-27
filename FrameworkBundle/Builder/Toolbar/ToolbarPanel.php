<?php

namespace LooopCore\FrameworkBundle\Builder\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;
use LooopCore\FrameworkBundle\Builder\Toolbar\ActionsCollection;
use LooopCore\FrameworkBundle\Builder\Toolbar\Events\ToolbarEvents;
use LooopCore\FrameworkBundle\Builder\Toolbar\Events\LoadActionsEvent;

class ToolbarPanel extends AbstractPanelBuilder
{  
    const TOOLBAR_PANEL_NAME = 'toolbar_panel';
    
    /** @var ActionsCollection */
    protected $actions;
    
    public function __construct(Container $serviceContainer, 
                                array $options = array())
    {
        parent::__construct($serviceContainer, $options);
        
        $this->actions = new ActionsCollection();
        $this->riseLoadToolbarActionsEvent();
    }        
    
    public function getName()
    {
        return ToolbarPanel::TOOLBAR_PANEL_NAME;
    }        
        
    public function getActions()
    {
        return $this->actions->getAll();
    }        
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Toolbar', 
                                     'source' => 'LooopCoreFrameworkBundle:Toolbar/Toolbar:renderPanel'));        
    }  
    
    protected function riseLoadToolbarActionsEvent()
    {
        $event = new LoadActionsEvent($this->actions);
        $this->dispatcher->dispatch(ToolbarEvents::ON_TOOLBAR_LOAD_ACTIONS,
                                         $event);
    }
}

