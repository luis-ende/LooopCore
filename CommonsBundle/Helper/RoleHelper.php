<?php
namespace LooopCore\CommonsBundle\Helper;

use LooopCore\CommonsBundle\Entity\User;
use LooopCore\FrameworkBundle\Helper\ServiceHelper;

/**
 * Class to check if the given user has a role in the role hierarchy
 *
 * @author didi
 */
class RoleHelper {
    
    private static $container;
    
    /**
     * return the global security context
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    public static function getSecurityContext() {
        return ServiceHelper::getServiceContainer()->get("security.context");
    }


    /**
     * Checks, if the given user has one of the given ro9les
     * @param User $user a User object or null
     * @param array $roles a role given as STring or an array of roles
     */
    public static function userHasRole($user, $roles) {
        if (!$user) {
            return false;
        }
        if (!is_array($roles)) {
            $roles = array($roles);
        }
        $allUserRoles = self::getUserParentRoles($user);
        if (array_intersect($roles, $allUserRoles)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param User $user
     */
    public static function getUserParentRoles($user) {
        return ServiceHelper::getServiceContainer()->get("looopcore.security.global_role_hierarchy")
                ->getUserParentRoles($user);
    }
    
    /**
     * returns an array of all roles. Keys are the roles, values an array of parent roles
     */
    public static function getGlobalRoleHierarchy() {
        return ServiceHelper::getServiceContainer()->get("looopcore.security.global_role_hierarchy")->getRoles();
    }
    
    public static function getRoleHierarchy() {
        return ServiceHelper::getServiceContainer()->get("security.role_hierarchy")->getRoles();
    }
    
    /**
     * returns a key=>value array containing role names as keys and simplified role names as values
     */
    public static function getGlobalRolesArrayForForms($withSonataRoles = true) {
        
        
        $roles = array_keys(self::getRoleHierarchy());
        $roles_names = [];
        foreach($roles as $role) {
            $roles_names[] = str_replace("ROLE_", "", $role);
        }
        $rolesArray = array_combine($roles, $roles_names);;
        if ($withSonataRoles) {
            $sonataRoles = self::getSonataAdminRoles();
            $rolesArray["SONATA"] = self::getSonataAdminRoles();
        }
        return $rolesArray;
        
    }

    /**
     * get Array with roles created by SonataAdminBundle
     */
    public static function getSonataAdminRoles() {
        $serviceContainer = ServiceHelper::getServiceContainer();
        $sonataAdminPool = $serviceContainer->get("sonata.admin.pool");
        $sonataRolesBuilder = new \Sonata\UserBundle\Security\EditableRolesBuilder(
                $serviceContainer->get("security.context"), $sonataAdminPool);
        return $sonataRolesBuilder->getRoles();
    }
    
    
}

?>
