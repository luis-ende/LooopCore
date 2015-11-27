<?php

namespace LooopCore\FrameworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use LooopCore\FrameworkBundle\Factory\ViewBuilderFactoryInterface;
use LooopCore\FrameworkBundle\Builder\Grid\AbstractGridBuilder;


/**
 * Base Controller that can be used by all Actions / Controllers
 * and has common functionality
 */
class BaseController extends Controller 
{
    public function getContainer() 
    {
        return $this->container;
    }  
    
    /**
     * Creates and activates a view builder object through the factory service.
     * 
     * @param type $viewBuilderKey
     * @param array $params
     * @return type ViewBuilder
     */
    protected function activateViewBuilder($viewBuilderKey, array $params = array()) 
    {
        /* @var $viewBuilderFactory ViewBuilderFactoryInterface */
        $viewBuilderFactory = $this->get('looopcore.viewbuilder_factory');        
        $viewBuilder = $viewBuilderFactory->createAndActivate($viewBuilderKey, 
                                                              $params);
                        
        return $viewBuilder;
    }        
}
