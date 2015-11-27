<?php

namespace LooopCore\FrameworkBundle\Builder\Grid;

use LooopCore\FrameworkBundle\Builder\ViewBuilderInterface;

/**
 * Base interface of a grid builder. Provides access to a datagrid object
 * which represents the column structure of a tabular view.
 */
interface GridBuilderInterface extends ViewBuilderInterface
{           
    public function setDataGrid($grid);    
    public function getDataGrid();
    public function initializeDataGrid();
}