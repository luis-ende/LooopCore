<?php

namespace LooopCore\CommonsBundle\Security\Roles;


/**
 * This class adds roles of a bundle
 * It has to be configured as a service.
 * See src/LooopCore/CommonsBundle/Resources/config/services.yml
 * 
 * This class has not to be copied or extended in other bundled, it 
 * is enough to define another service for this class
 * with the tag looopcore.bundle_roles
 */
class BundleRoleHierarchy {
    
    protected $roleHierarchy;
    
    public function __construct($roleHierarchy) {
        $this->roleHierarchy = $roleHierarchy["roles"];
    }
    
    public function getRoleHierarchy() {
        return $this->roleHierarchy;
    }
    
    
}

?>
