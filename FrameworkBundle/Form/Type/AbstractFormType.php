<?php

namespace LooopCore\FrameworkBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use LooopCore\FrameworkBundle\Builder\Form\FormNode;
use LooopCore\FrameworkBundle\Builder\Form\FormNodeInterface;
use LooopCore\FrameworkBundle\Builder\Form\FormNodeToBuilder;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvents;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvent; 
use LooopCore\FrameworkBundle\Builder\Form\FormBuilderEmptyDataEvent; 

abstract class AbstractFormType extends AbstractType 
                                implements FormNodeInterface 
{
        
    /**
     * @var FormNode
     */
    private $formModel;     
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;    
    
    public function __construct(EventDispatcherInterface $dispatcher) 
    {        
        $this->formModel = new FormNode();        
        $this->dispatcher = $dispatcher;
    }              
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {        
        $this->riseBeforeBuildFormModelEvent($builder->getForm());
        $this->buildCustomFormModel($options, $builder->getForm()->getData());
        $this->riseAfterBuildFormModelEvent($builder->getForm());

        $this->formModelToBuilder($builder);
    }

    /**
     * 
     * @return FormNode
     */
    public function getFormModel() 
    {
        return $this->formModel;
    }
    
    public function setFormModel(FormNode $formModel) 
    {
        $this->formModel = $formModel;
    }       
                       
    public function getName() {
        return get_class($this);
    }
              
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }
    
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);

        return $this;
    }               
    
    protected function buildCustomFormModel(array $options, $data = null) 
    {        
    }
    
    protected function formModelToBuilder(FormBuilderInterface $builder) 
    {
        $formModelToBuilder = new FormNodeToBuilder($builder, 
                                                     $this->getFormModel());
        $formModelToBuilder->formModelToBuilder();
    }
    
    protected function riseBeforeBuildFormModelEvent(Form $form) 
    {
        $event = new FormBuilderEvent($this, $form->getData());
        $this->dispatcher->dispatch(FormBuilderEvents::PRE_BUILD_FORM, $event);
    }
    
    protected function riseAfterBuildFormModelEvent(Form $form) 
    {
        $event = new FormBuilderEvent($this, $form->getData());
        $this->dispatcher->dispatch(FormBuilderEvents::POST_BUILD_FORM, $event);
    }       
    
    protected function riseEmptyDataEvent(FormInterface $form) 
    {        
        $event = new FormBuilderEmptyDataEvent($form, $this->getName());
        $this->dispatcher->dispatch(FormBuilderEvents::EMPTY_DATA_FORM, $event);
        return $event;
    }        
}