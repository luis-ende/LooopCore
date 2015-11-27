<?php
namespace LooopCore\FrameworkBundle\Helper;

/**
 * Class to deal with symfony services
 *
 * @author martin
 */
class ServiceHelper {
    
    /**
     * returns the SF2 Service container
     * @global type $kernel
     * @return ContainerInterface
     */
    public static function getServiceContainer() {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        return $kernel->getContainer();
    }
    /**
     * return a symfony2 helper with the given name
     * @param String $name
     * @return Object
     */
    public static function getHelperByName($name) {
        return self::getServiceContainer()->get($name);

    }
    
}

?>
