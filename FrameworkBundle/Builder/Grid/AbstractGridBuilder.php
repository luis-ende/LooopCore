<?php

namespace LooopCore\FrameworkBundle\Builder\Grid;

use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use APY\DataGridBundle\Grid\Export\XMLExport;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Controller\BaseController;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderColumnEvent;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderRowEvent;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderSourceEvent;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderInitializeEvent;
use LooopCore\FrameworkBundle\Builder\Grid\Events\GridBuilderEvents;
use LooopCore\FrameworkBundle\Builder\AbstractViewBuilder;
use LooopCore\FrameworkBundle\Builder\Exception\BuilderErrorException;

/**
 * Base abstract implementation of a grid builder. A grid builder is a
 * helper class used by a controller to organize and coordinate the elements 
 * of a single datagrid view (a tabular view of data).
 * Based on the APYDataGridBundle (see https://github.com/Abhoryo/APYDataGridBundle)
 */
abstract class AbstractGridBuilder extends AbstractViewBuilder 
                                   implements GridBuilderInterface
{                
    /**
     * @var Grid
     */
    protected $dataGrid = null;
       
    public function __construct(BaseController $controller, 
                                array $options = array())   
    {
        parent::__construct($controller, $options);
        
        if ($this->options['auto_initialize_grid_service']) {
            $this->setDataGrid($this->getGridService());
        }
    }
    
    /**
     * Returns the associated grid object of the view.
     * 
     * @return \APY\DataGridBundle\Grid\Grid
     */
    public function getDataGrid() 
    {
        return $this->dataGrid;
    }
    
    /**
     * Sets a new instance of the grid object to the view.
     * 
     * @param \APY\DataGridBundle\Grid\Grid $grid
     */
    public function setDataGrid($grid)
    {
        $this->dataGrid = $grid;
        $this->dataGrid->setId($this->getName());
    }
        
    /**
     * Render a response with given parameters.
     * This is necessary, because APY Grid View has a special render function.
     * All parameters / the view name can be given like with ordinary rendering in the controller.
     * 
     * @param Array $parameters
     * @param String $gridTemplate
     * @param String $viewName
     * @return Response
     */
    public function getViewResponse(array $parameters = array(), 
                                    $gridTemplate = null, $viewName = null) 
    {    
        try 
        {
            $this->initializeDataGrid();
        } catch (\Exception $e) {
            throw new BuilderErrorException('An error ocurred while initializing the data grid.', 
                                            0, $e);
        }        
        
        if (!$viewName) {
            $viewName = $this->options['view_template'];
        }
        if (!$gridTemplate) {
            $gridTemplate = !empty($this->options['grid_template']) ? 
                                         $this->options['grid_template'] : null;
        }           
        $parameters['grid_template'] = $gridTemplate;        
        
        return $this->dataGrid->getGridResponse($viewName, $parameters);
    } 
    
    /**
     * Triggers an event for every column of the grid that is rendered.
     * 
     * @param GridBuilderColumnEvent $event
     */
    public function onGridColumnRender(GridBuilderColumnEvent $event) 
    {
        $columnId = $event->column->getId();
        $this->callViewEvent('onColumnRender', $columnId, $event);                        
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(['auto_initialize_grid_service' => true,
                                'start_page' => 1, 
                                'pagination_limits' => [10, 20, 30],
                                'enable_export' => false,
                                'hide_default_actions_column' => true,
                                'hide_columns' => null,
                                'hide_titles' => null,
                                'allow_filters' => null,
                                'hide_titles' => null,
                                'allow_sorting' => null,
                                'allow_selection' => null,
                                'config_strategy' => 'looopcore.grid_builder_config_strategy',
                                'columns_order' => []]);
        $resolver->setRequired(['entity_name', 
                                'view_template', 
                                'grid_template']);
    }
                
    public function initializeDataGrid()
    {
        if (is_null($this->dataGrid)) {
            throw new \Exception('Data Grid component not assigned. Data Grid View cannot be initialized.');
        }

        $this->riseDataGridBeforeInitializeEvent();

        $dataSource = $this->buildDataSource();
        $this->setDataSource($dataSource);
        $this->setGridColumns();
        $this->setColumnsOrder();
        $this->setPaginationConfig();
        $this->setExportOptions();
        $this->applyExternalOptions();

        $this->dataGrid->isReadyForRedirect();
        
        $this->riseDataGridAfterInitializeEvent();                
    }
    
    protected function buildDataSource() 
    {
        return new Entity($this->options['entity_name']);
    }
    
    protected function setDataSource(Entity $dataSource)
    {                        
        $dataSource->manipulateRow(function ($row) {
            $viewEvent = new GridBuilderRowEvent($row);
            $this->dispatcher->dispatch(GridBuilderEvents::GRID_VIEW_ROW_MANIPULATE, 
                                        $viewEvent);
            
            return $viewEvent->row;
        });

        $dataSource->manipulateQuery(function ($query) use ($dataSource) {
            $viewEvent = new GridBuilderSourceEvent($query, $dataSource);
            $this->dispatcher->dispatch(GridBuilderEvents::GRID_VIEW_SOURCE_MANIPULATE, 
                                        $viewEvent);                                        
        });
                                    
        $this->dataGrid->setSource($dataSource); 
        $this->dataGrid->setNoDataMessage(false);                
    }        
            
    protected function setGridColumns() 
    {                        
    }

    protected function setColumnsOrder() {
        if (count($this->options['columns_order']) > 0) {
            $this->dataGrid->setColumnsOrder($this->options['columns_order']);
        }
    }
    
    protected function addRowAction($title, $route, $confirm = true, 
                                    $target = '_self', $attributes = array()) 
    {
        if (!$this->options['hide_default_actions_column']) {
            $rowAction = new RowAction(
                $title, $route, $confirm, $target,
                $attributes, null
            );
            $rowAction->setRouteParameters(['id']);
            $this->dataGrid->addRowAction($rowAction);
            $this->dataGrid->setActionsColumnTitle('');

            return $rowAction;
        }

        return null;
    }
    
    protected function addViewGridColumn($columnType, array $options, $onRenderCell = false)
    {
        $gridColumn = $this->getViewGridColumn($columnType, $options);
        $this->dataGrid->addColumn($gridColumn);

        if ($onRenderCell) {
            $this->registerGridColumnListener($gridColumn);
        }
        
        return $gridColumn;
    }

    protected function getViewGridColumn($columnType, array $options)
    {
        $type = null;

        if (is_bool($this->options['allow_sorting'])) {
            $options['sortable'] = $this->options['allow_sorting'];
        }

        if ($this->serviceContainer->has($columnType)) {
            $type = $this->serviceContainer->get($columnType);
        } else {
            if (class_exists($columnType)) {
                $type = new $columnType($options);
            } else {
                $apyGridColumn = "\APY\DataGridBundle\Grid\Column\\" . $columnType;
                if (class_exists($apyGridColumn))
                {
                    $type = new $apyGridColumn($options);
                }
            }
        }

        if (is_null($type)) {
            throw new Exception("Column Type '" . $columnType . "' was not found.");
        }

        $gridColumn = new $type($options);

        return $gridColumn;
    }
    
    private function registerGridColumnListener($gridColumn)
    {
        $gridColumn->manipulateRenderCell( 
            function ($value, $row, $router) use ($gridColumn) {
                $viewEvent = new GridBuilderColumnEvent($gridColumn, $value,
                                                     $row, $router);
                $this->dispatcher->dispatch(GridBuilderEvents::GRID_VIEW_COLUMN_RENDER, 
                                            $viewEvent);
                return $viewEvent->cellValue;                
        });
    }
    
    protected function setPaginationConfig() {
        $this->dataGrid->setLimits($this->options['pagination_limits']);        
        $this->dataGrid->setPage($this->options['start_page']);
    }
    
    protected function setExportOptions()
    {
        if ($this->options['enable_export']) {
            $this->dataGrid->addExport(new CSVExport('CSV Export'));
            $this->dataGrid->addExport(new ExcelExport('Excel Export'));
            $this->dataGrid->addExport(new XMLExport('XML Export'));
        }
    }            

    protected function applyExternalOptions()
    {
        if (is_array($this->options['hide_columns'])) {
            $this->dataGrid->hideColumns($this->options['hide_columns']);
        }

        if (is_bool($this->options['allow_filters']) &&
            (!$this->options['allow_filters'])) {
            $this->dataGrid->hideFilters();
        }

        if ((is_bool($this->options['hide_titles'])) &&
            ($this->options['hide_titles'])) {
            $this->dataGrid->hideTitles();
        }

        if (is_bool($this->options['allow_selection']) &&
            ($this->options['allow_selection'])) {
            $massAction = new MassAction(' ', [$this, function($primaryKeys, $allPrimaryKeys, $session, $parameters) {  }]);
            $this->dataGrid->addMassAction($massAction);
        }
    }

    private function getGridService()
    {        
        return $this->serviceContainer->get('grid');
    }           
    
    protected function registerViewEvents() 
    {
        parent::registerViewEvents();
        
        $this->registerEventListener(GridBuilderEvents::GRID_VIEW_COLUMN_RENDER, 
                                     array($this, 'onGridColumnRender'));
    }
    
    private function riseDataGridBeforeInitializeEvent()
    {
        $eventBefore = new GridBuilderInitializeEvent($this);
        $this->dispatcher->dispatch(GridBuilderEvents::GRID_BEFORE_BUILD, 
                                    $eventBefore);
    }
            
    private function riseDataGridAfterInitializeEvent()
    {
        $eventAfter = new GridBuilderInitializeEvent($this);
        $this->dispatcher->dispatch(GridBuilderEvents::GRID_AFTER_BUILD, 
                                    $eventAfter);
    }        
}