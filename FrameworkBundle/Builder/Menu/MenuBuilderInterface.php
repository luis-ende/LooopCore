<?php

namespace LooopCore\FrameworkBundle\Builder\Menu;

use LooopCore\FrameworkBundle\Builder\UIBuilderInterface;

/**
 * Base interface of a menu builder. Provides access to the tree of elements
 * of a menu.
 *
 */
interface MenuBuilderInterface extends UIBuilderInterface
{
    public function getCurrentMenuTree();
}
