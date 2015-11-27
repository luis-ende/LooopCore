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
class GlobalRoleHierarchy {
    
    protected $roleHierarchy;
    
    public function __construct() {
        $this->roleHierarchy = array();
    }
    
    public function addBundleRoleHierarchy(BundleRoleHierarchy $bundleRoleHierarchy) {
        foreach($bundleRoleHierarchy->getRoleHierarchy() as $roleDefinitionArray) {
            $this->roleHierarchy = 
                array_merge($this->roleHierarchy, $roleDefinitionArray);
        }
    }
    
    /**
     * returns an array of roles
     * @return array
     */
    public function getRoles() {
        return $this->roleHierarchy;
    }
    
     /** 
     * returns the parent roles recursively of the given user
      * -> all roles that are part of the role hierarchy are returned
     * @param string $role
     * @return Array
     */
    public function getUserParentRoles(\LooopCore\CommonsBundle\Entity\User $user) {
        $userRoles = $user->getRoles();
        $allParentRoles = array();
        foreach($userRoles as $userRole) {
            foreach($this->getParentRoles($userRole) as $parentRole) {
                if (!in_array($parentRole, $allParentRoles)) {
                    $allParentRoles[] = $parentRole;
                }
            }
        }
        return $allParentRoles;
    }
    
    /** 
     * returns the parent roles recursively of the given role or null
     * -> all roles that are part of the role hierarchy are returned
     * @param string $role
     * @return Array
     */
    public function getParentRoles($role) {
        //$allParentRoles = array($role);
        $allParentRoles = array($role);
        
        if (!array_key_exists($role, $this->roleHierarchy)) {
            return $allParentRoles;
        }
        $parentRoles = $this->roleHierarchy[$role];
        foreach($parentRoles as $parentRole) {
            foreach($this->getParentRoles($parentRole) as $recursiveParentRole) {
                if (!in_array($recursiveParentRole, $allParentRoles)) {
                    $allParentRoles[] = $recursiveParentRole;
                }
            }
        }
        return $allParentRoles;
    }

}

?>
