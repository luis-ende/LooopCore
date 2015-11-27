<?php

namespace LooopCore\CommonsBundle\Entity;

use LooopCore\FrameworkBundle\Entity\EntityInterface;

// Disable Strict error reporting for this class
// -> FOSUserBundle defines $id which is also present
// in Generated class -> Warning should be ignored
$oldErrRep = error_reporting();
if ($oldErrRep & E_STRICT) {
    error_reporting($oldErrRep ^ E_STRICT);
}

/**
 * Entity (model class) Group

 * This class inherits the generated class Group ans has all the attributes,
 * that are also present in the database or mapping.

 * This class is for methods, not directly coming from the DB
 */
class Group extends \FOS\UserBundle\Entity\Group implements EntityInterface {
    use Generated\Group;

    use \LooopCore\FrameworkBundle\Entity\EntityTrait;
    
    public function __construct($name, $roles = array()) {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct($name, $roles);
    }
        
    public function __toString() {
        return $this->getName();
    }
    
    public function getRoles2() {
        return array();
    }


}


error_reporting($oldErrRep);