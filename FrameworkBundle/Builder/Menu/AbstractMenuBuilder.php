<?php

namespace LooopCore\FrameworkBundle\Builder\Menu;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Knp\Menu\FactoryInterface;

use LooopCore\FrameworkBundle\Builder\AbstractUIBuilder;
use LooopCore\FrameworkBundle\Builder\Menu\MenuNode;
use LooopCore\FrameworkBundle\Builder\Menu\Events\MenuBuilderEvent;
use LooopCore\FrameworkBundle\Builder\Menu\Events\MenuBuilderEvents;

/**
 * Helper to build a menu structure for rendering in a twig template.
 */
abstract class AbstractMenuBuilder extends AbstractUIBuilder
                                   implements MenuBuilderInterface
{
    /**     
     * @var type FactoryInterface
     */
    protected $factory;
    
    /**     
     * @var MenuNode
     */
    protected $menuTree;
    
    public function __construct(FactoryInterface $factory, 
                                Container $serviceContainer, 
                                array $options = array())
    {
        parent::__construct($serviceContainer);
        
        $this->factory = $factory;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCurrentMenuTree() 
    {
        return $this->menuTree;
    }
    
    /**
     * Builds and returns a menu tree representation to be rendered.
     * 
     * @param array $options array of options (currently ignored)
     * @return MenuNode Tree representation of a menu
     */
    abstract protected function buildMenuTree(array $options = array());
    
    /**
     * Creates a Knp menu object.
     *          
     * @param array $options array of options (currently ignored)
     * @return \Knp\Menu\MenuItem
     */
    public function getMenu(array $options = array()) 
    {     
        $this->menuTree = $this->buildMenuTree($options);
        $this->configMenuTree();    
        
        return $this->menuNodeToMenuItem($this->menuTree);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(['config_strategy' => 
                                    'looopcore.menu_builder_config_strategy',]);
    }
    
    /**
     * Creates a KNP menu from LooopNodes.
     *
     * The name of the root node will be used as a name for the menu.
     * @access private
     * @param FactoryInterface $factory the factory to create the menu with
     * @param MenuNode $menuNode the root of the LooopNode-tree
     * @return \Knp\Menu\MenuItem
     */
    protected function menuNodeToMenuItem(MenuNode &$menuNode) {        
        /* @var $node \Knp\Menu\ItemInterface */
        $node = $this->factory->createItem($menuNode->getName(), 
                                           $menuNode->getOptions());   
                
        $extras = $menuNode->getOption('extras');
        if (!is_null($extras)) {
            foreach ($extras as $key => $value) {
                $node->setExtra($key, $value);
            }
        }            

        // add the children
        foreach ($menuNode->getChildren() as $menuChild) {
            $child = $this->menuNodeToMenuItem($menuChild);                        
            $node->addChild($child);
        }

        return $node;
    }   
    
    /**
     * Apply configuration and subscribed customizations to the menu structure
     * before rendering.
     */
    protected function configMenuTree()
    {
        if (null != $this->menuTree) {
            $event = new MenuBuilderEvent($this->menuTree);
            $this->dispatcher->dispatch(MenuBuilderEvents::MENU_AFTER_BUILD, 
                                        $event);        
            $this->getConfig()->applyConfig();
        }        
    }
}