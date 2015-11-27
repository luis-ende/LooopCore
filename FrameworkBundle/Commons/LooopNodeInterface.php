<?php

namespace LooopCore\FrameworkBundle\Commons;

interface LooopNodeInterface
{
    /**
     * Get the name of the node
     *
     * Each child of a node must have a unique name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the options for the factory to create the item for this node
     *
     * @return array
     */
    public function getOptions();

    /**
     * Get the child nodes implementing LooopNodeInterface
     *
     * @return \Traversable
     */
    public function getChildren();
}