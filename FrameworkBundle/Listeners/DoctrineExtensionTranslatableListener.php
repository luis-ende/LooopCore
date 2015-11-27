<?php

namespace LooopCore\FrameworkBundle\Listeners;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DoctrineExtensionTranslatableListener implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    public function onKernelRequest(GetResponseEvent $event) {
        
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onLateKernelRequest(GetResponseEvent $event) {
        $translatable = $this->container->get('gedmo.listener.translatable');
        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }


}
