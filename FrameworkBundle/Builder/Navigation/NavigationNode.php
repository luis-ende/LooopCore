<?php
namespace LooopCore\FrameworkBundle\Builder\Navigation;

use LooopCore\FrameworkBundle\Commons\LooopNode;

/**
 * Default implementation of NavigationElementInterface.
 */
class NavigationNode extends LooopNode
                     implements NavigationElementInterface
{     
    private $sourceId;
    private $displayText;    
    private $elementType;    
    private $index;        
    
    public function getSourceId()
    {
        return $this->sourceId;
    }
    
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    public function getParent()
    {
        
    }
    
    public function getChildren() 
    {
        
    }

    public function getDisplayText()
    {
        return $this->displayText;
    }
    
    public function setDisplayText($displayText)
    {
        $this->displayText = $displayText;
    }    
   
    public function getElementType()
    {
        return $this->elementType;
    }
    
    public function setElementType($type)
    {
        $this->elementType = $type;
    }

    public function getIndex()
    {
        $this->index;
    }    
    
    public function setIndex($index)
    {
        $this->index = $index;
    }
    
    public function getElementPath() 
    {
        return '';
    }
}