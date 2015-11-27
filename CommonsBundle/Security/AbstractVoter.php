<?php

namespace LooopCore\CommonsBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\SecurityContext;

use LooopCore\CommonsBundle\Entity\User;


/**
 * Base class for alls voters defined in LLP Bundles
 */
abstract class AbstractVoter implements VoterInterface {
    
    protected $responsibleFor = null;
    protected $possibleAttributes  = array();
    protected $container = null;


    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    /**
     * Returns true, if this voter is responsible for the given 
     * class (e.g. entity class)
     * -> if no class is given and this voter is nopt responsible
     *    for a special class, this also matches
     */
    public function supportsClass($object) {
        if (is_string($object)) {
            return $object == $this->responsibleFor;
        } else {
            return $object instanceof $this->responsibleFor;
        }
    }

    /**
     * Returns true, if this voter can answer the given
     * attribute (string) -> this is a custom privilege like "write", "delete", "sendMail" etc
     */
    public function supportsAttribute($attribute) {
        if (!is_array($attribute)) {
            $attribute = array($attribute);
        }
        return sizeof(array_intersect($attribute, $this->possibleAttributes));
    }
    
    public function isResponsible($object, array $attributes) {
        return $this->supportsClass($object) && $this->supportsAttribute($attributes);
    }
    
    /**
     * Returns the logged in user or null, if not logged in
     * @param TokenInterface $token
     * @return User
     */
    public function getLoggedInUser(TokenInterface $token) {
        if ($token->getUser() == "anon.") {
            return null;
        }
        return $token->getUser();
    }
    
    /**
     * Return the security context from the container given in the constructor
     * @return SecurityContext
     */
    public function getSecurityContext() {
        return $this->container->get('security.context');
    }

}

?>
