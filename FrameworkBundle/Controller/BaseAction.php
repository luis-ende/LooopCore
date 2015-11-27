<?php

namespace LooopCore\FrameworkBundle\Controller;

use Exception;
use LooopCore\FrameworkBundle\Controller\BaseController;
use LooopCore\FrameworkBundle\Builder\Grid\GridBuilderInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Tests\String;

/**
 * Base Action to use by all action classes.
 * An action technically is a controller that has a class "run"
 * 
 * Actions should be placed in the controller namespace, so that there is the structure
 *  \<MyBundle>\Controller\<MyController>\<MyAction>
 * 
 * In the routing, the action class can be used like a controller class, with the
 * "runAction" method being called
 * 
 */
class BaseAction extends BaseController 
{            
    /**
     * Get the name of the default twig template for this action
     * 
     * @return String
     */
    public function getDefaultViewName() {
        $reflectionClass = new ReflectionClass($this);
        $namespaceExploded = explode('\\', $reflectionClass->getName());
        $controllerName= array_reverse($namespaceExploded)[1];
        $className = array_reverse($namespaceExploded)[0];
        $actionName = lcfirst(preg_replace("/Controller$/", "", $className));
        $bundleName = $namespaceExploded[0] . $namespaceExploded[1];
        
        return "$bundleName:$controllerName:$actionName.html.twig";
    }            
    
    /**
     * Render a grid view with the default template and given optional parameters.
     * By default, the gridTemplate is defined in the GridView definition class
     * See also renderGridView
     * 
     * @param AbstractGridBuilder $gridView
     * @param Array $parameters
     * @return Response
     */
    public function renderGridDefaultView(GridBuilderInterface $gridView, $parameters = []) {
        return $this->renderGridView($gridView, null, $parameters);
    }
    
    /**
     * Render a grid view with given optional parameters.
     * By default, the gridTemplate is defined in the GridView definition class
     * A view can be given, otherwise the default view is rendered
     * 
     * @param AbstractGridBuilder $gridView
     * @param type $viewName
     * @param type $parameters
     * @param type $gridTemplate
     * @return Response
     */
    public function renderGridView(GridBuilderInterface $gridView, $viewName = null, $parameters = [], $gridTemplate = null) {
        if (!$viewName) {
            $viewName = $this->getDefaultViewName();
        }
        return $gridView->getViewResponse($parameters, $gridTemplate, $viewName);
    }
    
    protected function activateAndRenderGridView($viewId, array $options = array())
    {
        $viewBuilder = $this->activateViewBuilder($viewId, $options);
        
        if ($viewBuilder instanceof GridBuilderInterface) {
            return $this->renderGridDefaultView($viewBuilder);
        } else {
            throw new \InvalidArgumentException('The view could not be rendered as grid view.');
        }
            
    }
    
    /**
     * Abstrakte Methode, muss in Kindklasse überschrieben werden.
     * Darf nicht als abstract deklariert werden, da die Anzahl und Typen der 
     * Parameter dynamisch ist und vom Routing abhängt
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws Exception
     */
//    protected function runAction(Request $request) {
//        throw new Exception("Action Not implemented!");
//    }
    
    /**
     * Render view derived from Action/Controller Name
     * -> views/<controller>/<action>.html.twig
     * 
     * @param array $parameters
     * @return Response A Response instance
     */
    protected function renderDefaultView($parameters = []) {
        return $this->render($this->getDefaultViewName(), $parameters);
        
    }
}