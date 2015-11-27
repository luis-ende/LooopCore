<?php

namespace LooopCore\FrameworkBundle\Builder\Navigation;

final class NavigationElementType 
{
    const NAVIGATION_ELEMENT_TYPE_CURRICULAR_ELEMENT = 'nav_curric_element';
    const NAVIGATION_ELEMENT_TYPE_LINK = 'nav_link';    
    
    public $id;
    public $text;
    public $navigationType;
    
    public function __construct($id, $text, $navigationType)
    {
        $this->id = $id;
        $this->text = $text;
        $this->navigationType = $navigationType;
    }        
}