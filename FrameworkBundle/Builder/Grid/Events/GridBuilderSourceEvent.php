<?php

namespace LooopCore\FrameworkBundle\Builder\Grid\Events;

use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;

class GridBuilderSourceEvent extends Event {
    
    private $queryBuilder;
    private $dataSource;    
    
    public function __construct(QueryBuilder $queryBuilder, $dataSource) 
    {
        $this->queryBuilder = $queryBuilder;
        $this->dataSource = $dataSource;
    }        
    
    /**
     *  @return QueryBuilder  */
    public function getQueryBuilder() 
    {
        return $this->queryBuilder;
    }
    
    /**
     *  @return Entity */
    public function getDataSource() 
    {
        return $this->dataSource;
    }
    
}