<?php
namespace LooopCore\CommonsBundle\Helper;

use LooopCore\CommonsBundle\Entity\User;
use LooopCore\CommonsBundle\Helper\RoleHelper;

/**
 * Class to manage (logged in) users globally
 *
 * @author Martin
 */
class UserHelper {
    

    /**
     * Gets the logged in user from any place of the code.
     * returns null if no user is logged in.
     * @return User the user object or null
     */
    public static function getLoggedInUser() {
        $tokenUser = RoleHelper::getSecurityContext()->getToken()->getUser();
        if ($tokenUser == "anon.") {
            return null;
        } else {
            return $tokenUser;
        }
    }
   
    
}

?>
