<?php

namespace LooopCore\CommonsBundle\Controller\Panel;

use LooopCore\FrameworkBundle\Controller\BaseAction;
use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderInterface;

class SubviewController extends BaseAction 
{
    public function renderPanelAction(PanelBuilderInterface $panel)
    {
        return $this->renderDefaultView(array('views' => $this->getSubviews()));                
    }
    
    protected function getSubviews()
    {
        return $views = ['Lehrveranstaltungen', 'Lernziele', 'Stundenplan'];
    }
}