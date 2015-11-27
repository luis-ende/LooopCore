<?php

namespace LooopCore\CommonsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LooopCoreCommonsBundle extends Bundle
{
    public function build(ContainerBuilder $container) 
    {
        parent::build($container);
        $container->addCompilerPass(new Security\Roles\BundleRolesCompilerPass());     
    }
}
