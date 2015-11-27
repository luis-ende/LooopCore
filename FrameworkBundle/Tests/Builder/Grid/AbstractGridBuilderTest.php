<?php

namespace LooopCore\FrameworkBundle\Tests\Builder\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use APY\DataGridBundle\Grid\Grid;

use LooopCore\FrameworkBundle\Controller\BaseController;
use LooopCore\FrameworkBundle\Builder\Grid\AbstractGridBuilder;

class AbstractGridBuilderTest extends WebTestCase 
{
    public function testCreateGridBuilder() 
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        
        /* @var $controller \LooopCore\FrameworkBundle\Controller\BaseAction */
        $controller = new BaseController();        
        $controller->setContainer($container);
        $gridBuilder = new TestGridBuilder($controller);
        
        $this->assertNotNull($gridBuilder);
    }   
    
    public function testSetDataGrid()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        
        /* @var $controller \LooopCore\FrameworkBundle\Controller\BaseAction */
        $controller = new BaseController();        
        $controller->setContainer($container);
        $gridBuilder = new TestGridBuilder($controller);
        
        
        $grid = $this->getMockBuilder('Grid')
                     ->disableOriginalConstructor()
                     ->setMethods(['setId'])
                     ->getMock();                        
        $gridBuilder->setDataGrid($grid);
        
        $this->assertNotNull($gridBuilder->getDataGrid());
    }
            
    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testCreateGridBuilderWithoutRequiredOptionsThrowAnException() 
    {
        static::bootKernel();
        
        $controller = $this->getMockBuilder(\LooopCore\FrameworkBundle\Controller\BaseController::class)                                                
                           ->getMock();        
        $controller->method('getContainer')
                   ->willReturn(static::$kernel->getContainer());                
        
        $gridBuilder = new TestGridBuilderWithoutOptions($controller);
    }
}

class TestGridBuilder extends AbstractGridBuilder
{
    public function getName()
    {
        return "test_viewbuilder";
    }

    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array('auto_initialize_grid_service' => false,
                                     'entity_name' => 'TestEntityForTestGridBuilder', 
            // option parameters 'view_template' and 'grid_template' must always be configured
                                     'view_template' => 'test', 
                                     'grid_template' => 'test'));        
    }
}

class TestGridBuilderWithoutOptions extends AbstractGridBuilder
{
    public function getName()
    {
        return "test_viewbuilder";
    }
}

class TestEntityForTestGridBuilder 
{
    
}