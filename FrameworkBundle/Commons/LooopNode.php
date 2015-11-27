<?php

namespace LooopCore\FrameworkBundle\Commons;

/**
 * Class implemented by a node to construct a tree of elements with options.
 *
 * LooopNode adds some extra functionality like retrieving a child by its features.
 */
class LooopNode implements LooopNodeInterface 
{

    /**
     * Array of LooopNodes.
     *
     * @access private
     * @var array array of LooopNode
     */
    protected $children = [];

    /**
     * Array of options.
     *
     * @see LooopNode::setOptions()
     * @access private
     * @var array
     */
    protected $options = [];

    /**
     * The unique name of the node.
     *
     * @access private
     * @var string
     */
    protected $name = "";

    /**
     * Get the name of the node implementing NodeInterface.
     *
     * Each child of a node must have a unique name.
     * @access public
     * @return string
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * Get the options for the factory to create the item for this node implementing NodeInterface.
     * @access public
     * @return array
     */
    public function getOptions() 
    {
        return $this->options;
    }    
    
    public function getOption($key) 
    {
        if (array_key_exists($key, $this->options)) {        
            return $this->options[$key];
        } else {
            return null;
        }                
    }

    /**
     * Get the child nodes implementing NodeInterface.
     * @access public
     * @return \Traversable
     */
    public function getChildren() 
    {
        return $this->children;
    }

    /**
     * Get the number of child nodes implementing NodeInterface.
     * @access public
     * @return int
     */
    public function hasChildren() 
    {
        return count($this->children) > 0;
    }                    

    /**
     * Constructor.
     *
     * @access public
     * @param string $name a unique $name of the node
     * @param array $options an array of options @see LooopNode::setOptions()
     * @return LooopNode
     */
    public function __construct($name, array $options = array()) 
    {
        $this->setName($name);
        $this->setOptions($options);

        return $this;
    }

    /**
     * Appends a child to the children of this node.
     *
     * @access public
     * @param LooopNode $child a LooopNode
     * @param array $options an array of options @see LooopNode::setOptions()
     * @return LooopNode the appended child
     */
    public function appendChild($child, array $options = array()) 
    {
        $this->children[] = $child;

        return $child;
    }

    /**
     * Sets the name of the node.
     *
     * If this is the root node $name will be used as the name of the menu.
     * The name of the node has to be unique in the tree.
     * @access public
     * @param string $name the name of the node
     */
    public function setName($name) 
    {
        $this->name = $name;
    }
    
    /**
     * Sets the options of the node.
     * 
     * @param array $options
     */
    public function setOptions(array $options) 
    {
        $this->options = array_merge($this->options, $options);
    }

    public function setOption($key, $value) 
    {
        $this->options[$key] = $value;
    }
    
    /**
     * Loops through all children and returns the node with $this->_name === $name if present.
     *
     * @access public
     * @param string $name the name of the node to find
     * @return LooopNode the node
     */
    public function getNodeByName($name) 
    {
        $result = null;

        foreach ($this->children as $child) {
            if ($child->getName() === $name) {
                return $child;
            }

            $result = $child->getNodeByName($name);

            if ($result !== null) {
                return $result;
            }
        }
    }   
}
