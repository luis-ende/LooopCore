<?php

namespace LooopCore\CommonsBundle\Controller\Panel;

use LooopCore\FrameworkBundle\Controller\BaseAction;
use LooopCore\FrameworkBundle\Builder\Panel\PanelBuilderInterface;

class ExportController extends BaseAction 
{
    public function renderPanelAction(PanelBuilderInterface $panel)
    {        
        return $this->renderDefaultView(array('exports' => $this->getExports()));
    }
    
    protected function getExports()
    {
        return $modeOfExport = ['ical', 'odt', 'xlsx'];
    }
}