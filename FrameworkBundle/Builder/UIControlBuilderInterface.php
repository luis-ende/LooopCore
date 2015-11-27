<?php

namespace LooopCore\FrameworkBundle\Builder;

interface UIControlBuilderInterface extends UIBuilderInterface
{
    public function getTitle();
    
    public function getVisible();
       
    public function getEnabled();        
}