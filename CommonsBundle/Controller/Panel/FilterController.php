<?php

namespace LooopCore\CommonsBundle\Controller\Panel;

use LooopCore\FrameworkBundle\Controller\BaseAction;
use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderInterface;

class FilterController extends BaseAction 
{
    public function renderPanelAction(PanelBuilderInterface $panel)
    {
        return $this->renderDefaultView(array('filters' => $this->getFilters()));        
    }
    
    protected function getFilters()
    {
        $filters = array(
            array(
            'name' => 'Fächer',
            'options' => array ('alle Fächer', 'F01', 'F02'),
            'id' => 'looop_bar_discipline'),
            array(
            'name' => 'Dimensionen',
            'options' => array ('alle Lernzieldimensionen', 'Wissen/Kenntnisse', 'Fertigkeiten'),
            'id' => 'looop_bar_dimension'),
            array(
            'name' => 'Lehrformate nach StdOrd',
            'options' => array ('alle Lehrformate (StdOrd)', 'Vorlesung', 'Seminar'),
            'id' => 'looop_bar_type_1'),
            array(
            'name' => 'Lehrformate',
            'options' => array ('alle Lehrformate', 'Vorlesung', 'Seminar'),
            'id' => 'looop_bar_type_2'));
        
        return $filters;
    }
}