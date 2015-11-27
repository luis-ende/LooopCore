<?php

namespace LooopCore\CommonsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use LooopCore\CommonsBundle\Entity\Group;
use LooopCore\CommonsBundle\Entity\User;

class AuthController extends Controller
{
    
    /**
     * @Template("LooopCoreCommonsBundle:Auth:login.html.twig")
     */
    public function loginAction() {
        return array();
    }
    
    
        
    public function addAdminAction() {
        $user = User::getRepository()->findOneBy(["username"=>"admin"]);
        if ($user) {
            return new \Symfony\Component\HttpFoundation\Response("Admin wurde schon angelegt!");
        }
        
        $user = new User();
        $user->setUsername("admin");
        $user->setFirstName("admin");
        $user->setLastName("admin");
        $user->setPlainPassword("admin");
        $user->setEnabled(true);
        $user->setEmail("llp@charite.de");
        $user->save();
        
        
        $group = new Group("superAdminGrup", array("ROLE_SUPER_ADMIN"));
        $group->save();
        
        /* @var $user User */
        $user->addGroup($group);
        $user->save();
        
        return $this->forward("LooopCoreCommonsBundle:Index:index");

    }

}
