<?php

namespace LooopCore\FrameworkBundle\Entity;

/**
 * Der Trait für alle Superklassen für alle Entitäts-Repositories. Hier können Methoden festgelegt werden,
 * die ohne eine konkrete Entity aufgerufen werden können, sowie spezielle Methoden zum Speiuchern etc.
 */
trait RepositoryTrait{
    
    /**
     * Base function to save an entity of the type this repository is responsible for.
     * Default: Just use doctrine save on the entity.
     * Can be overridden by entity repository subclasses.
     * 
     * @param Object $entity
     * @return boolean
     */
    public function save($entity) {
        $em = $entity->getEntityManager();
        $em->persist($entity);
        $em->flush($entity);
        
        return true;
    }
}