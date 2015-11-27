<?php

namespace LooopCore\FrameworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LooopCoreFrameworkBundle extends Bundle
{
    public function build(ContainerBuilder $container) 
    {
        parent::build($container);        
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterListenersPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterViewBuildersPass());
    }
}
