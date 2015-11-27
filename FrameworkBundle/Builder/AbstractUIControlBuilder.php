<?php

namespace LooopCore\FrameworkBundle\Builder;

abstract class AbstractUIControlBuilder extends AbstractUIBuilder
                                        implements UIControlBuilderInterface
{
    protected $title = "";
    protected $visible = true;
    protected $enabled = true;    
    
    public function setVisible($visible)
    {
        $this->options['visible'] = $visible;
    }
    
    public function getVisible()
    {
        return $this->options['visible'];
    }
    
    public function setEnabled($enabled)
    {
        $this->options['enabled'] = $enabled;
    }
    
    public function getEnabled()
    {
        return $this->options['enabled'];
    }
    
    public function setTitle($title)
    {
        $this->options['title'] = $title;
    }
    
    public function getTitle()
    {
        return $this->options['title'];
    }   
}