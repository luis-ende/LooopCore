<?php

namespace LooopCore\FrameworkBundle\Tests\Builder;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use LooopCore\FrameworkBundle\Builder\AbstractUIBuilder;

class AbstractUIBuilderTest extends KernelTestCase 
{
    public function testCreateUIBuilder() 
    {
        static::bootKernel();
        $uiBuilder = new TestUIBuilder(static::$kernel->getContainer());        
        
        $this->assertNotNull($uiBuilder);
    }
    
    public function testCreateUIBuilderWithOptions() 
    {
        static::bootKernel();
        $uiBuilder = new TestUIBuilderWithOptions(static::$kernel->getContainer(), 
                                                  ['custom_option' => 'test']);
        
        $this->assertNotNull($uiBuilder);
    }
    
    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testCreateUIBuilderFailsIfARequiredOptionWasNotProvided() 
    {
        static::bootKernel();
        new TestUIBuilderWithOptions(static::$kernel->getContainer());                
    }
    
    
    /**
     * @expectedException \LooopCore\FrameworkBundle\Builder\Exception\BuilderConfigurationException
     */
    public function testGetConfigurationFailsIfNoValidStrategyWasProvided() {
        static::bootKernel();
        $uiBuilder = new TestUIBuilder(static::$kernel->getContainer(), 
                                       ['config_strategy' => 'test_strategy']);
        
        $uiBuilder->getConfig();
    }
    
    /**
     * @expectedException \LooopCore\FrameworkBundle\Builder\Exception\MissingConfigStrategyException
     */
    public function testGetConfigurationFailsIfNoStrategyWasProvided() {
        static::bootKernel();
        $uiBuilder = new TestUIBuilder(static::$kernel->getContainer());
        
        $uiBuilder->getConfig();
    }        
}


// Clases only for test purposes 

class TestUIBuilder extends AbstractUIBuilder
{
    public function getName()
    {
        return "test_uibuilder";
    }
}

class TestUIBuilderWithOptions extends AbstractUIBuilder
{
    public function getName()
    {
        return "test_uibuilder";
    }
    
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setRequired(['custom_option']);
    }
}