<?php

namespace LooopCore\CommonsBundle\Security\Roles;

use Symfony\Component\Security\Core\Role\RoleHierarchy;


/**
 * This class adds roles of a bundle
 * It has to be configured as a service.
 * See src/LooopCore/CommonsBundle/Resources/config/services.yml
 * 
 * This class has not to be copied or extended in other bundled, it 
 * is enough to define another service for this class
 * with the tag looopcore.bundle_roles
 */
class SymfonyRoleHierarchyOverride extends RoleHierarchy{
    
    private $symfonyRoles = [];
    /**
     * override symfony service method
     */
    public function __construct($hierarchy = null)
    {
        $this->symfonyRoles = $hierarchy;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * Override symfony service method -> get the role hierarchy from self-build services
     * @return array
     */
    private function buildRolesTree()
    {
        $globalHierarchy = \LooopCore\CommonsBundle\Helper\RoleHelper::getGlobalRoleHierarchy();
        return array_merge($this->symfonyRoles, $globalHierarchy);
    }
    
    public function getRoles() {
        return $this->map;
    }
            
            
    
}

?>
