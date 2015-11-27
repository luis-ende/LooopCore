<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvents;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvent;

/**
 * Base class listener object on triggered events of a LLPFormType.
 */
abstract class AbstractFormBuilderSubscriber implements EventSubscriberInterface 
{

    public static function getSubscribedEvents() 
    {
        return array(FormBuilderEvents::PRE_BUILD_FORM => 'preBuildForm',
                     FormBuilderEvents::POST_BUILD_FORM => 'postBuildForm');
    }

    final public function preBuildForm(FormBuilderEvent $event) 
    {
        if ($this->getSubscribedFormName() == $event->getForm()->getName()) {
            $this->preBuild($event);
        }
    }

    final public function postBuildForm(FormBuilderEvent $event) 
    {
        if ($this->getSubscribedFormName() == $event->getForm()->getName()) {
            $this->postBuild($event);
        }
    }

    public function preBuild(FormBuilderEvent $event) 
    {        
    }

    public function postBuild(FormBuilderEvent $event) 
    {        
    }

    /**
     * The name of the form that should be handled
     */
    abstract public function getSubscribedFormName();

}
