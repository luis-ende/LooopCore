<?php
namespace LooopCore\FrameworkBundle\Helper;

/**
 * Class to deal with models / repostitories
 * Functions should not be needed to called in projects, but 
 * can be called by BaseEntity trait to encapsulate access to  repositories
 *
 * @author martin
 */
class ModelHelper {
    
    private static $_entityManager = null;
    
    /**
     * Get Entity Manager globally.
     * 
     * Method can be modified for tests to get another entity manager when testing.
     * 
     * TODO??: Entity manager is passed as service, not as global
     * 
     * @global $kernel
     * @return type
     */
    public static function getEntityManager() {
        if (!self::$_entityManager) {
            global $kernel;
            self::$_entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        }
        return self::$_entityManager;
    }
    
        
    /**
     * Get Repository for a class globally.
     * 
     * Method can be modified for tests to get another repository when testing.
     * 
     * @param String $entityName
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager (optional)
     * @global $kernel
     * @return type
     */
    public static function getRepository($entityName, $entityManager = null) {
        if (!$entityManager) {
            $entityManager = self::getEntityManager();
        }
        return $entityManager->getRepository($entityName);
        
    }
    
}

?>
