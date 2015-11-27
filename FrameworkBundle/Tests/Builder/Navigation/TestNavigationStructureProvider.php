<?php

namespace LooopCore\FrameworkBundle\Tests\Builder\Navigation;

use LooopCore\FrameworkBundle\Builder\Navigation\NavigationStructureProviderInterface;
use LooopCore\FrameworkBundle\Builder\Navigation\NavigationNode;

class TestNavigationStructureProvider implements NavigationStructureProviderInterface
{
    private $counter = 0;
    
    public function provideNavigationTree()
    {
        $testNavigationTree = array();                
        $testNavigationTree[] = $this->createNode('parent1');
        $testNavigationTree[] = $this->createNode('parent2');
        $testNavigationTree[] = $this->createNode('parent3');
        $testNavigationTree[] = $this->createNode('parent4');
        $testNavigationTree[] = $this->createNode('parent5');
        
        return $testNavigationTree;
    }

    public function refreshNavigationTree(array $navigationTree)
    {
        
    }
    
    private function createNode($title) 
    {
        $node = new NavigationNode($title);
        $node->setSourceId($this->counter++);
        $node->setDisplayText($title);
        
        return $node;
    }

}