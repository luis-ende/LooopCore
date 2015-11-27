<?php

namespace LooopCore\FrameworkBundle\Builder\Menu;

use LooopCore\FrameworkBundle\Commons\LooopNode;

use Knp\Menu\NodeInterface;

/**
 * Class implementation of a menu tree structure.
 */
class MenuNode extends LooopNode implements NodeInterface
{
    public function __construct($name, $label = '', array $options = array()) 
    {
        $options['label'] = $label;
        
        parent::__construct($name, $options);
    }

    public function appendChild($child, $label = '', array $options = array()) 
    {
        if (is_string($child)) {
            $child = new MenuNode($child, $label, $options);
        }
        
        parent::appendChild($child);
    }
    
    /**
     * Sets the options of the node.
     *
     * The following options can be specified (KnpMenu):
     * - 'route' => string
     * - 'uri' => string
     * - 'label' => string
     * - 'attributes' => string
     * - 'linkAttributes' => string
     * - 'childrenAttributes' => string
     * - 'labelAttributes' => string
     * - 'display' => string
     * - 'displayChildren' => string
     * - 'extras' => array          
     * @param array $options an array of options as specified above
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
    }
    
    /**
     * Get the route if it is set.
     * @access public
     * @return string
     */
    public function getRoute() 
    {
        if (isset($this->options['route'])) {
            return $this->options['route'];
        } else {
            return '';
        }
    }
    
    /**
     * Loops through all children and returns the node with $this->_options['route'] === $route if present.
     *
     * @access public
     * @param string $route the route of the node to find
     * @return LooopNode the node
     */
    public function getNodeByRoute($route) 
    {
        $result = null;

        foreach ($this->children as $child) {
            $options = $child->getOptions();
            if ((isset($options['route'])) && ($options['route'] === $route)) {
                return $child;
            }

            $result = $child->getNodeByRoute($route);

            if ($result !== null) {
                return $result;
            }
        }
    }

    /**
     * Loops through all children and returns the nodes with $this->_options['extras'][$attribute] === $value if present.
     *
     * @access public
     * @param string $attribute the attribute to look at
     * @param string $value the value to filter by
     * @return array array of LooopNode
     */
    public function getNodesByAttribute($attribute, $value) 
    {
        $result = [];

        foreach ($this->children as $child) {
            $options = $child->getOptions();
            if (!isset($options['extras'])) {
                continue;
            }

            if ((isset($options['extras'][$attribute])) && ($options['extras'][$attribute] === $value)) {
                $result[] = $child;
            }

            $result = array_merge($result, $child->getNodesByAttribute($attribute, $value));
        }

        return $result;
    }
}