<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use LooopCore\FrameworkBundle\Commons\LooopNode;

/*
 * Mantains a hierarchical model elements (tabs, fields) of a form 
 * for later rendering.
 */
class FormNode extends LooopNode
{
    public function __construct($id = 0, $type = null, $index = 1, 
                                array $options = null, $visible = true) 
    {
        $this->options['id'] = $id;
        $this->options['type'] = $type;
        $this->options['index'] = $index;
        $this->options['options'] = $options;
        $this->options['visible'] = $visible;
        
        parent::__construct($this->options['id'], $this->options);
                
    }    
    
    public function appendChildForm($id = 0, $type = null, $index = 1, 
                                array $options = null, $visible = true)
    {        
        return parent::appendChild(new FormNode($id, $type, $index, 
                                                   $options, $visible));
    }        
    
    public function getFormOptions()
    {
        return $this->options['options'];
    }
    
    public function hasFormOption($key) 
    {
        return array_key_exists($key, $this->options['options']);
    }
    
    public function getFormOption($key)
    {
        return $this->options['options'][$key];        
    }
    
    public function setFormOptions(array $options)
    {
        $this->options['options'] = $options;
    }
    
    public function setFormOption($key, $value) 
    {
        $this->options['options'][$key] = $value;
    }
    
    public function getItemPosition($id) 
    {
        $position = 0;
        foreach($this as $key => $value) {            
            $position++;
            if ($id == $key) {                
                return $position;
            }                
        }
        
        return $position;
    }
    
    public function sortItems() 
    {                
        uasort($this->children, array($this, 'compareItems'));
    }
    
    public function compareItems($a, $b) 
    {
        if ($a->options['index'] == $b->options['index']) {
            return 0;
        }
        
        return ($a->options['index'] < $b->options['index']) ? -1 : 1;
    }    
}