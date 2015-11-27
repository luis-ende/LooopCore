<?php

namespace LooopCore\FrameworkBundle\Builder\Panel;

use Symfony\Component\OptionsResolver\OptionsResolver;

use LooopCore\FrameworkBundle\Builder\AbstractUIControlBuilder;

abstract class AbstractPanelBuilder extends AbstractUIControlBuilder
                                    implements PanelBuilderInterface
{    
    protected $source = null;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
    }        
    
    public function setSource($source)
    {
        $this->options['source'] = $source;
    }
    
    public function getSource()
    {
        return $this->options['source'];
    }
        
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setRequired(['source', 'title']);
        $resolver->setDefaults(['config_strategy' => 'looopcore.panel_builder_config_strategy', 
                                'visible' => true, 
                                'enabled' => true]);        
    }
}
