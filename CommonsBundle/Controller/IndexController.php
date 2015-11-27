<?php

namespace LooopCore\CommonsBundle\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LooopCore\CommonsBundle\Entity\User;
use LooopCore\CommonsBundle\Helper\RoleHelper;
use LooopCore\CommonsBundle\Helper\UserHelper;


class IndexController extends Controller
{
    
    /**
     * @Route("/", name="_myIndex")
     * @Template("LooopCoreCommonsBundle:Index:index.html.twig")
     */
    public function indexAction()
    {
        $globalRoleHierarchy = RoleHelper::getRoleHierarchy();
        $allUsers = User::getRepository()->findAll();
        $allUserRoles = array();

        if ($this->getUser()) {
            $allUserRoles = $this->get("looopcore.security.global_role_hierarchy")->getUserParentRoles($this->getUser());
        }
        
        $rolesInConf = $this->get("security.role_hierarchy");
        
//        $group = new \LooopCore\CommonsBundle\Entity\Group("test", array("ROLE_llpBase_EXAMPLE", "ROLE_ADMIN"));
//        $group->save();
//        
//        $user = User::find(1);
//        /* @var $user \LLP\TestBundle\Entity\User */
//        $user->addGroup($group);
//        $user->save();
        
        
        $loggedInUser = UserHelper::getLoggedInUser();
        
        return array("allUsers"=>$allUsers, "allRoles"=>$globalRoleHierarchy, "allUserRoles"=> $allUserRoles, "rolesInConf" => $rolesInConf);
    }


}
