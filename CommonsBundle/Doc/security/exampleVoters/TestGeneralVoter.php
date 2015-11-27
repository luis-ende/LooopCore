<?php

namespace LooopCore\CommonsBundle\Security\GeneralVoters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use LooopCore\CommonsBundle\Security\AbstractVoter;

/**
 * given a User object, this class decides whether the user has
 * access to a privilege (attribute) or not
 */
class TestGeneralVoter extends AbstractVoter {
    
    protected $responsibleFor = "TESTGENERAL";
    protected $possibleAttributes  = array("DOSOMETHING");

    /**
     * Security definitions
     * For every possible attribute (String) given,
     * decide if access is granted or denied.
     *
     * @param TokenInterface $token - a token containing the logged in user
     * @param $object null
     * @param Array $attributes - if several attributes are given, return true if 
     * any of them would return true (=OR)
     */
    public function vote(TokenInterface $token, $object, array $attributes) {
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
        
        
        // DOSOMETHING //
        // Demonstration
        if (in_array("DOSOMETHING", $attributes)) {
                if (rand(0, 1)) {
                return self::ACCESS_GRANTED;
            }
        }
        
        return self::ACCESS_DENIED;
    }
}

?>
