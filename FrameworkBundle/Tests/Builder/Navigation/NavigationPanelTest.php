<?php

namespace LooopCore\FrameworkBundle\Tests\Builder\Navigation;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use LooopCore\FrameworkBundle\Builder\Navigation\NavigationPanelBuilder;
use LooopCore\FrameworkBundle\Builder\Navigation\NavigationElementInterface;

class NavigationPanelTest extends KernelTestCase
{
    public function testNavigationPanelCreate()
    {
        static::bootKernel();                
        $navigationPanel = new NavigationPanelBuilder(static::$kernel->getContainer());
        
        $this->assertNotNull($navigationPanel);                                
    }
    
    public function testNavigationPanelSetProvider()
    {        
        $navTree = $this->getNavigationTree();
        
        $this->assertNotNull($navTree);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNavigationPanelGetNavigationTreeNoProvider() 
    {
        static::bootKernel();        
        $navigationPanel = new NavigationPanelBuilder(static::$kernel->getContainer());        
        $navTree = $navigationPanel->getNavigationTree();                
    }
    
    public function testNavigationPanelSetCurrentElement()
    {   
        static::bootKernel();
        $provider = new TestNavigationStructureProvider();        
        $navigationPanel = new NavigationPanelBuilder(static::$kernel->getContainer());                        
        $navigationPanel->setNavigationProvider($provider);
        
        $currentId = 2;
        $navigationPanel->setCurrentNavigationElement($currentId);
        
        /* @var $currentElement NavigationElementInterface */
        $currentElement = $navigationPanel->getCurrentNavigationElement();
        $this->assertNotNull($currentElement);
        $this->assertTrue($currentElement->getSourceId() === $currentId);                 
    }
    
    private function getNavigationTree()
    {
        static::bootKernel();
        $provider = new TestNavigationStructureProvider();        
        $navigationPanel = new NavigationPanelBuilder(static::$kernel->getContainer());                        
        $navigationPanel->setNavigationProvider($provider);
        $navTree = $navigationPanel->getNavigationTree();
        
        return $navTree;
    }
}