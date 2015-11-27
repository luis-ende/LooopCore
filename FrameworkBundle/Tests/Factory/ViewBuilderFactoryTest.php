<?php

namespace LooopCore\FrameworkBundle\Tests\Factory;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use LooopCore\FrameworkBundle\Factory\ViewBuilderFactory;
use LooopCore\FrameworkBundle\Factory\ViewBuilderRegistry;

class ViewBuilderFactoryTest extends KernelTestCase
{
    public function testCreateViewBuilder() 
    {
        $factory = $this->getTestFactory($this->getTestWorkspace());                                     
        $view = $factory->create(TestViewBuilder::TESTVIEWBUILDER_NAME);
        
        $this->assertInstanceOf(TestViewBuilder::class, $view);
    }               
    
    public function testActivateViewBuilder() 
    {
        $workspace = $this->getTestWorkspace();
        $factory = $this->getTestFactory($workspace);
        $view = $factory->createAndActivate(TestViewBuilder::TESTVIEWBUILDER_NAME);
        
        $this->assertEquals($view, $workspace->getCurrentView());
    }
        
    protected function getTestFactory($workspace) 
    {
        $registry = new ViewBuilderRegistry(array(TestViewBuilder::TESTVIEWBUILDER_NAME =>
                                                  TestViewBuilder::class));        
        $factory = new ViewBuilderFactory($registry, $workspace);
        
        return $factory;
    }
    
    protected function getTestWorkspace()
    {
        return new TestWorkspaceBuilder();
    }
}