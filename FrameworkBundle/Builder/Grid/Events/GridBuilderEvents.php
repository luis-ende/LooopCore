<?php

namespace LooopCore\FrameworkBundle\Builder\Grid\Events;

/**
 * Provides constants to identify a group of events associated to the 
 * a view's grid builder.
 */
final class GridBuilderEvents 
{
    const GRID_VIEW_ROW_MANIPULATE = 'gridview.row.manipulate';
    const GRID_VIEW_SOURCE_MANIPULATE = 'gridview.source.manipulate';
    const GRID_VIEW_COLUMN_RENDER = 'gridview.column.render';    
    const GRID_BEFORE_BUILD = 'gridview.pre_build';
    const GRID_AFTER_BUILD = 'gridview.post_build';
}