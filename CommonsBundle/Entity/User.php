<?php

namespace LooopCore\CommonsBundle\Entity;

use FOS\UserBundle\Model\GroupInterface;

use LooopCore\FrameworkBundle\Entity\EntityInterface;
use LooopCore\CommonsBundle\Helper\RoleHelper;


// Disable Strict error reporting for this class
// -> FOSUserBundle defines $id which is also present
// in Generated class -> Warning should be ignored
$oldErrRep = error_reporting();
if ($oldErrRep & E_STRICT) {
    error_reporting($oldErrRep ^ E_STRICT);
}

/**
 * Entity (model class) User

 * This class inherits the generated class User ans has all the attributes,
 * that are also present in the database or mapping.

 * This class is for methods, not directly coming from the DB
 */
class User extends \FOS\UserBundle\Entity\User implements EntityInterface {
    use Generated\User;
    use \LooopCore\FrameworkBundle\Entity\EntityTrait;
    
    /**
     * Add explicit constructor to avoid conflict of trait / superclass constructors
     */
    public function __construct() {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct();
    }

    
    /**
     * Roles that are saved directly at the user, NOT at the group.
     * Normally, Roles should be saved in groups.
     * Please use ONLY $this->getRoles() for security checks.
     */
    public function getDirectRoles() {
        return $this->roles;
    }
    public function addDirectRole($role) {
        $this->addRole($role);
    }
    public function removeDirectRole($role) {
        $this->removeRole($role);
    }
    
    
    /** override methode because of trait compatibility 
     * @return Group[]
     */
    public function getGroups() {
        return parent::getGroups();
    }
    
    /** override methode because of trait compatibility */
    public function addGroup(GroupInterface $group) {
        return parent::addGroup($group);
    }

    /** override methode because of trait compatibility */
    public function removeGroup(GroupInterface $group) {
        return parent::removeGroup($group);
    }

    /**
     * return an array with all roles including all parent roles
     * of this user
     * @return array
     */
    public function getAllRolesWithParentRoles() {
        return RoleHelper::getUserParentRoles($this);
    }
    
    public function getAllRolesWithParentRolesString($removePrefix = true) {
        $returnString =  implode(", ", $this->getAllRolesWithParentRoles());
        if ($removePrefix) {
            $returnString = str_replace("ROLE_", "", $returnString);
        }
        return $returnString;
    }
    
    /**
     * return if the user has the given role in the role hierarchy
     * or one of the given roles, if an array of roles is given
     * @param Array or String A role or an array of roles
     * @return boolean
     */
    public function hasRole($roles) {
        return RoleHelper::userHasRole($this, $roles);
    }
    
    public function __toString() {
        return $this->getUsername() ? $this->getUsername() : "";
    }
    
    /**
     * 
     */
    public function getFullName() {
        
    }
}

error_reporting($oldErrRep);