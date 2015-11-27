<?php

namespace LooopCore\FrameworkBundle\Builder\Workspace;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;
use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Workspace\WorkspaceBuilderInterface;

class WorkspaceBuilder extends AbstractPanelBuilder
                       implements WorkspaceBuilderInterface
{    
    const WORKSPACE_BUILDER_NAME = 'workspace_panel';
          
    /*
     * @var ViewBuilderInterface
     */
    protected $currentView = null;
    
    public function getName()
    {
        return WorkspaceBuilder::WORKSPACE_BUILDER_NAME;
    }
    
    public function getCurrentView()
    {
        return $this->currentView;
    }
    
    public function setCurrentView(ViewBuilderInterface $view)
    {
        $this->currentView = $view;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Workspace', 
                                     'source' => ''));        
    }  
}