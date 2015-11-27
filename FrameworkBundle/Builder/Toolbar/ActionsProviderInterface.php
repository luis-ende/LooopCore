<?php

namespace LooopCore\FrameworkBundle\Builder\Toolbar;

/**
 * Interface should be implemented in classes who provide action items for the
 * toolbar.
 */
interface ActionsProviderInterface
{
    /**
     * @returns array | Array of AbstractToolbarActionBuilder
     */
    public function getActions();
}