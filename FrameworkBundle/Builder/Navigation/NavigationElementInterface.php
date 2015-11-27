<?php

namespace LooopCore\FrameworkBundle\Builder\Navigation;

/**
 * TODO:
 * Methoden umbenennen (z.B. Präfix überall), so dass es keine Namenskollision geben kann
 * mit Methoden aus den implementierenden Klassen, und jede Methode explizit implementiert
 * werde muss.
 *
 * TODO: Methoden entfernen, die für Navigation nicht benutzt werden, z.B. getText()...
 *
 * TODO: Evt. Interface-Methoden hinzufügen, die für Navigation gebraucht werden
 *
 * Interface NavigationElementInterface
 * @package LooopCore\FrameworkBundle\Builder\Navigation
 */
interface NavigationElementInterface
{
    // todo-me Methoden umbenennen mit dem Prefix 'Navigation'

    /**
     * @return integer
     */
    public function getSourceId();
    
    /**
     * @return string
     */
    public function getDisplayText();
    
    /**     
     * @return NavigationElementType
     */
    public function getElementType();        
    
    /**
     * @return integer
     */
    public function getIndex();        
            
    /**
     * @return array of NavigationElementInterface
     */
    public function getChildren();
    
    /**
     * @return NavigationElementInterface
     */
    public function getParent();        
    
    /**
     * @return string
     */
    public function getElementPath(); 
}