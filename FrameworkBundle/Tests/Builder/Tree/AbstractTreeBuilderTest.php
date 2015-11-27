<?php

namespace Builder\Tree;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use LooopCore\FrameworkBundle\Builder\Tree\AbstractTreeBuilder;
use LooopCore\FrameworkBundle\Builder\Tree\TreeWrapper;
use LooopCore\FrameworkBundle\Controller\BaseController;

class AbstractTreeBuilderTest extends WebTestCase
{
    public function testCreateTreeBuilder()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        /* @var $controller \LooopCore\FrameworkBundle\Controller\BaseAction */
        $controller = new BaseController();
        $controller->setContainer($container);
        $treeBuilder = new TestTreeBuilder($controller);

        $this->assertNotNull($treeBuilder);
    }

    public function testGetViewDefaultParameters()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        /* @var $controller \LooopCore\FrameworkBundle\Controller\BaseAction */
        $controller = new BaseController();
        $controller->setContainer($container);
        $treeBuilder = new TestTreeBuilder($controller);
        $treeBuilder->initializeTree();

        $viewParams = $treeBuilder->getViewParameters();

        $this->assertCount(1, $viewParams);
    }

    public function testGetViewTreeParameter()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        /* @var $controller \LooopCore\FrameworkBundle\Controller\BaseAction */
        $controller = new BaseController();
        $controller->setContainer($container);
        $treeBuilder = new TestTreeBuilder($controller);
        $treeBuilder->initializeTree();

        $viewParams = $treeBuilder->getViewParameters();
        $tree = $viewParams['tree'];

        $this->assertNotNull($tree);
    }
}

class TestTreeBuilder extends AbstractTreeBuilder
{
    public function getName()
    {
        return "test_viewbuilder";
    }

    protected function loadTree()
    {
        $child1 = new TreeWrapper(1, 'item 1');
        $child11 = new TreeWrapper(11, 'item 1.1');
        $child1->appendChild($child11);
        $this->dataTree->appendChild($child1);
        $child2 = new TreeWrapper(2, 'item 2');
        $this->dataTree->appendChild($child2);
        $child3 = new TreeWrapper(3, 'item 3');
        $child31 = new TreeWrapper(31, 'item 3.1');
        $child3->appendChild($child31);
        $this->dataTree->appendChild($child3);
    }
}