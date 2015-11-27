<?php
namespace LooopCore\CommonsBundle\Entity\AdminDefs;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use LooopCore\CommonsBundle\Helper\RoleHelper;

class GroupAdmin extends \Sonata\UserBundle\Admin\Entity\GroupAdmin {
    protected function configureFormFields(FormMapper $form) {
        $roles = RoleHelper::getGlobalRolesArrayForForms();
        
        $form
            //->add("id")
            ->add("name")
//            ->add('roles', 'sonata_security_roles', array(
//                'expanded' => false,
//                'multiple' => true,
//                'required' => false
//            ))
            ->add('roles', 'choice', 
                    array('label' => "Globale Rollen", 'required' => false, 'expanded' => false, 'multiple' => true,  
                        'choices' => $roles))
                    
            ;            
    }
    
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add("id")
            ->add("name")
            ->add("roles")
            ->add("createdAt")
            ->add("updatedAt");

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add("id")
            ->add("name")
            ->add("roles")
            ->add("createdAt")
            ->add("updatedAt")
                
                
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
            ->add("id")
            ->add("name")
            ->add("roles")
            ->add("createdAt")
            ->add("updatedAt");
    }
}
