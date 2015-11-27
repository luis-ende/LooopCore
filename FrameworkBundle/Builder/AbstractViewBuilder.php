<?php

namespace LooopCore\FrameworkBundle\Builder;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Controller\BaseAction;
use LooopCore\FrameworkBundle\Controller\BaseController;

/**
 * Base abstract implementation of a builder for different types of 
 * application views.
 */
abstract class AbstractViewBuilder extends AbstractUIBuilder 
                                   implements ViewBuilderInterface
{
    /**
     * @var BaseController
     */
    protected $controller;       
    
    public function __construct(BaseController $controller, 
                                array $options = array())   
    {
        $this->controller = $controller;

        parent::__construct($controller->getContainer(), $options);
    }

    public function getViewParameters($prefix = "form", $formVariableName = "form") 
    {       
        return [];
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {                   
        parent::setDefaultOptions($resolver);
                
        $options = array();
        if ($this->controller instanceof BaseAction) {
            $options['view_template'] =  $this->controller->getDefaultViewName();
        }                
        
        $resolver->setDefaults($options);
    }
    
    public function getActions()
    {        
        return array();
    }

    protected function validateViewConstructorParam($param, $paramClass)
    {
        if (is_null($param)) {
            throw new \Exception("An instance of the " . $paramClass . " class was "
                . "expected as a parameter for the constructor of a "
                . get_class($this)  . " class, NULL was given");
        }
    }
}
