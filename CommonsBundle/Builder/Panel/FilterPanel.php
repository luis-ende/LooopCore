<?php

namespace LooopCore\CommonsBundle\Builder\Panel;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;

class FilterPanel extends AbstractPanelBuilder
{
    protected $filters;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'filter_panel';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Filter', 
                                     'source' => 'LooopCoreCommonsBundle:Panel/Filter:renderPanel'));        
    }                             
}

