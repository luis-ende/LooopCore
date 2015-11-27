<?php

namespace LooopCore\CommonsBundle\Builder\Panel;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;

class ExportPanel extends AbstractPanelBuilder
{
    public function getName()
    {
        return 'export_panel';
    }   
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Export', 
                                     'source' => 'LooopCoreCommonsBundle:Panel/Export:renderPanel'));        
    }        
}

