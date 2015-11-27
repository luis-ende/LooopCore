<?php

namespace Builder\Tree;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use LooopCore\FrameworkBundle\Builder\Tree\TreeWrapper;

class TreeDataWrapperTest extends WebTestCase
{
    public function testGetViewTreeJsonParameter()
    {
        $treeDataWrapper = $this->getTestTreeDataWrapper();
        $treeJson = $treeDataWrapper->getTreeDataAsJson();

        $this->assertEquals('{"id":0,"item":[{"id":1,"text":"item 1","item":[{"id":11,"text":"item 1.1"}]},' .
            '{"id":2,"text":"item 2"},{"id":3,"text":"item 3","item":[{"id":31,"text":"item 3.1"}]}]}',
            $treeJson);
    }

    private function getTestTreeDataWrapper()
    {
        $root = new TreeWrapper(0, 'root');

        $child1 = new TreeWrapper(1, 'item 1');
        $child11 = new TreeWrapper(11, 'item 1.1');
        $child1->appendChild($child11);
        $root->appendChild($child1);
        $child2 = new TreeWrapper(2, 'item 2');
        $root->appendChild($child2);
        $child3 = new TreeWrapper(3, 'item 3');
        $child31 = new TreeWrapper(31, 'item 3.1');
        $child3->appendChild($child31);
        $root->appendChild($child3);

        return $root;
    }
}