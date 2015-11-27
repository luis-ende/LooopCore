<?php
namespace LooopCore\CommonsBundle\Entity\AdminDefs;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use LooopCore\CommonsBundle\Helper\RoleHelper;

class UserAdmin extends \Sonata\UserBundle\Admin\Entity\UserAdmin {
    protected function configureFormFields(FormMapper $form) {
        $roles = RoleHelper::getGlobalRolesArrayForForms();
        
        $form
            //->add("id")
            ->add("username")
//            ->add("usernameCannonical")
            ->add("email")
//            ->add("email_cannonical")
            ->add("firstName", null, array('label' => 'show.label_firstname'))
            ->add("lastName", null, array('label' => 'show.label_lastname'))
            ->add("plainPassword", "repeated", 
                    array("type"=>"password", "required"=>false,
                        'first_options' => array('label' => 'Passwort'),
                        'second_options' => array('label' => 'Passwort (Wiederholung)'),
                        'invalid_message' => 'fos_user.password.mismatch',
                        ))
            ->add('groups', null, array("required"=>false))
            ->add("enabled", null, array("required"=>false))
            ->add("locked", null, array("required"=>false))
            ->add("expired", null, array("required"=>false))
            ->add('directRoles', 'choice', 
                    array('label' => "Persönliche Rollen (besser Gruppen verwenden!)", 'required' => false, 'expanded' => false, 'multiple' => true,  
                        'choices' => $roles))

//            ->add("expiresAt")
//            ->add("passwordRequestedAt")
            //->add("roles", null, array("required"=>false))
            ->add("credentialsExpired", null, array("required"=>false));
//            ->add("credentialsExpireAt");
            
    }
    
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add("id")
            ->add("username")
//            ->add("usernameCannonical")
            ->add("email")
//            ->add("email_cannonical")
//            ->add("firstName")
//            ->add("lastName")
            ->add("enabled")
            ->add("roles")
            ->add("groups")
            ->add("locked")
            ->add("expired")
//            ->add("expiresAt")
            ->add("passwordRequestedAt")
            ->add("credentialsExpired")

                ;
//            ->add("credentialsExpireAt");

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add("id")
            ->add("username")
//            ->add("usernameCannonical")
            ->add("email")
            ->add("groups", null, array("editable"=>true))
            ->add("directRoles", "array", array("label"=>"personal roles"))

//            ->add("email_cannonical")
//            ->add("firstName")
//            ->add("lastName")
            ->add("enabled")
            ->add("locked")
            ->add("expired")
                
                
        ->add('_action', 'actions', array(
              'actions' => array(
                'show' => array(),
                'edit' => array(),
                //'delete' => array(),
            )
        ))
        ;
    }
    
    protected function configureShowFields(ShowMapper $filter) {
        $filter
            //->add("id")
            ->add("username")
//            ->add("usernameCannonical")
            ->add("email")
            ->add("allRolesWithParentRolesString")
//            ->add("email_cannonical")
//            ->add("firstName")
//            ->add("lastName")
            ->add("enabled")
            ->add("locked")
            ->add("expired")
//            ->add("expiresAt")
            ->add("passwordRequestedAt")
            ->add("roles")
            ->add("groups")
            ->add("credentialsExpired");
//            ->add("credentialsExpireAt");
    }
}
