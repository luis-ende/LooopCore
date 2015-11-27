<?php

namespace LooopCore\CommonsBundle\Builder\Panel;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\Panel\AbstractPanelBuilder;

class SubviewPanel extends AbstractPanelBuilder
{
    public function getName()
    {
        return 'subview_panel';
    } 
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
                
        $resolver->setDefaults(array('title' => 'Ansichtsmodi', 
                                     'source' => 'LooopCoreCommonsBundle:Panel/Subview:renderPanel'));        
    }        
}

