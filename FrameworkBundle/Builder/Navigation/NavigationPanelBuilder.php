<?php

namespace LooopCore\FrameworkBundle\Builder\Navigation;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;
use LooopCore\FrameworkBundle\Builder\Navigation\NavigationPanelBuilderInterface;
use LooopCore\FrameworkBundle\Builder\Navigation\NavigationStructureProviderInterface;
use LooopCore\FrameworkBundle\Builder\Toolbar\Events\ToolbarEvents;
use LooopCore\FrameworkBundle\Builder\Toolbar\Events\LoadActionsEvent;
use LooopCore\FrameworkBundle\Builder\Toolbar\ActionsCollection;

/**
 * Service to provide access to the navigation structure of the platform.
 */
class NavigationPanelBuilder extends AbstractPanelBuilder
                             implements NavigationPanelBuilderInterface
{
    const NAVIGATION_PANEL_NAME = 'navigation_panel';
    
    /** @var NavigationStructureProviderInterface */
    protected $provider;
    
    /** @var array */
    protected $navigationStructure;   
    
    /** @var NavigationPanelBuilderInterface */
    protected $currentElement;
    
    /** @var ActionsCollection */
    protected $defaultActions;
    
    public function __construct(Container $serviceContainer, 
                                array $options = array())
    {
        parent::__construct($serviceContainer, $options);
        
        $this->defaultActions = new ActionsCollection();        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return NavigationPanelBuilder::NAVIGATION_PANEL_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getActions()            
    {
        $this->riseLoadNavigationPanelActionsEvent();
        return $this->defaultActions->getAll();
    }   
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Navigation', 
                                     'source' => ''));        
    }   

    /**
     * Sets a source provider for the navigation panel. A source provider is 
     * able to  obtain a navigation structure from a specific datasource.
     * 
     * @param NavigationStructureProviderInterface $provider
     */
    public function setNavigationProvider(NavigationStructureProviderInterface $provider)
    {
        $refresh = true;
        if (!isset($this->provider)) 
        {
            $this->provider = $provider;
            $refresh = false;
        }         
        $this->initNavigationStructure($refresh);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCurrentNavigationElement()
    {
        return $this->currentElement;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentNavigationElement($id)
    {
        $this->currentElement = $this->findCurricularElement($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getNavigationTree()
    {
        $this->initNavigationStructure(true);
        
        return $this->navigationStructure;
    }
    
    protected function initNavigationStructure($refresh) 
    {        
        if (isset($this->provider)) {
            try {            
                if ($refresh) {
                    $this->provider->refreshNavigationTree($this->navigationStructure);                
                } else {
                    $this->navigationStructure = 
                                       $this->provider->provideNavigationTree();
                }
            } catch (Exception $e) {
                throw new \Exception("An error ocurred while trying to get or "
                        . "refresh the navigation structure.", 0, $e);
            }
        } else {
            throw new \InvalidArgumentException("Provider not setted, the navigation structure could not be initialized.");
        }
    }       
    
    private function findCurricularElement($id) 
    {        
        foreach ($this->navigationStructure as $element) {                
            if ($element->getSourceId() == $id) {
                return $element;
            }
        }
        
        return null;
    }
    
    protected function riseLoadNavigationPanelActionsEvent()
    {
        $event = new LoadActionsEvent($this->defaultActions);
        $this->dispatcher->dispatch(ToolbarEvents::ON_NAVIGATIONPANEL_LOAD_ACTIONS, 
                                    $event);
    }
}

/** @todo Throw event everytime the current element changed (really needed?) */