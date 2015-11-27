<?php

namespace LooopCore\FrameworkBundle\Tests\Builder;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use LooopCore\FrameworkBundle\Builder\AbstractViewBuilder;

class AbstractViewBuilderTest extends KernelTestCase 
{
    public function testCreateViewBuilder() 
    {
        static::bootKernel();
        
        $controller = $this->getMockBuilder(\LooopCore\FrameworkBundle\Controller\BaseController::class)                                                
                           ->getMock();        
        $controller->method('getContainer')
                   ->willReturn(static::$kernel->getContainer());
        
        $viewBuilder = new TestAbstractViewBuilder($controller);
        
        $this->assertNotNull($viewBuilder);
    }
}

class TestAbstractViewBuilder extends AbstractViewBuilder
{
    public function getName()
    {
        return "test_viewbuilder";
    }

}   