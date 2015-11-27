<?php

namespace LooopCore\CommonsBundle\Security\Roles;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * This class is needed to add roles as a service
 */
class BundleRolesCompilerPass implements CompilerPassInterface{
    
    public function process(ContainerBuilder $container) {
        if (!$container->hasDefinition("looopcore.security.global_role_hierarchy")) {
            throw new \Exception("a service with the name 'looopcore.security.global_role_hierarchy' has to be defined!");
        }
        $definition = $container->getDefinition("looopcore.security.global_role_hierarchy");
        $bundleRoleDefinitions = $container->findTaggedServiceIds("looopcore.security.bundle_roles");
        foreach($bundleRoleDefinitions as $id => $tagAttributes) {
            foreach($tagAttributes as $attributes) {
                $definition->addMethodCall("addBundleRoleHierarchy", 
                        array(new Reference($id), null));
            }
        }
        
    }


    /**
     * returns an array of roles
     * @return array
     */
    public function getRoles() {
        return $this->roleHierarchy;
    }
    
}

?>
