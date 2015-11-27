<?php

namespace LooopCore\CommonsBundle\Security\EntityVoters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use LooopCore\CommonsBundle\Entity\User;
use LooopCore\CommonsBundle\Security\AbstractVoter;

/**
 * given a User object, this class decides whether the user has
 * access to a privilege (attribute) or not
 */
class UserVoter extends AbstractVoter {
    
    protected $responsibleFor = "LooopCore\CommonsBundle\Entity\User";
    protected $possibleAttributes  = array("CREATE", "EDIT", "DELETE", "MYCUSTOM");

    /**
     * Security definitions
     * For every possible attribute (String) given,
     * decide if access is granted or denied.
     *
     * @param TokenInterface $token - a token containing the logged in user
     * @param User $object the (user) Object to check
     * @param Array $attributes - if several attributes are given, return true if 
     * any of them would return true (=OR)
     */
    public function vote(TokenInterface $token, $object, array $attributes) {
	// Just return "I don't know" if this voter is not responsible
        if (!$this->isResponsible($object, $attributes)) {
            return self::ACCESS_ABSTAIN;
        }
        $loggedInUser = $this->getLoggedInUser($token);
        $securityContext = $this->getSecurityContext();
        
        // Anonymous access is denied
        if (!$loggedInUser) {
            return self::ACCESS_DENIED;
        }
        
        // Everything is allowed for admin
        if ($loggedInUser->hasRole("ROLE_ADMIN")) {
            return self::ACCESS_GRANTED;
        }
        
        // CREATE | DELETE //
        // only Admin may create or delete users (which is defined above, so here we only deny)
        if (in_array("CREATE", $attributes)
                || in_array("DELETE", $attributes)) {
            
                return self::ACCESS_DENIED;
        }
        
        if (in_array("CREATE", $attributes)
                || in_array("DELETE", $attributes)) {
            
            if ($securityContext->isGranted("EDIT", $object)) {
                return self::ACCESS_GRANTED;
            }
        }
        
        // EDIT //
        // A Person may edit/delete the own object
        if (in_array("EDIT", $attributes)) {
            if ($loggedInUser->hasRole("ROLE_ADMIN") || $object == $loggedInUser) {
                return self::ACCESS_GRANTED;
            }
        }
        
        // MYCUSTOM //
        // Use random true/false for demonstration
        if (in_array("MYCUSTOM", $attributes)) {
            if (rand(0, 1)) {
                return self::ACCESS_GRANTED;
            }
        }
        
        // in the end, if we couldn't grant anything until now, just deny.
        return self::ACCESS_DENIED;
    }
}

?>
