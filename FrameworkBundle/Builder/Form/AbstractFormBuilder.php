<?php

namespace LooopCore\FrameworkBundle\Builder\Form;

use LooopCore\FrameworkBundle\Controller\BaseController;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvent;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEmptyDataEvent;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderEvents;
use LooopCore\FrameworkBundle\Builder\Form\Events\FormBuilderSaveDataEvent;
use LooopCore\FrameworkBundle\Builder\Form\FormNodeInterface;
use LooopCore\FrameworkBundle\Entity\EntityInterface;
use LooopCore\FrameworkBundle\Builder\AbstractViewBuilder;
use LooopCore\FrameworkBundle\Builder\Exception\BuilderConfigurationException;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base abstract implementation of a form builder. A form builder is
 * a helper class used by a controller to organize and coordinate the elements 
 * of a single capture form.
 */
abstract class AbstractFormBuilder extends AbstractViewBuilder 
                                   implements FormBuilderInterface
{

    /** @var Form */
    private $form;
    
    protected $dataSource;
    
    /** @var bool */
    protected $isDataSaved;
        
    protected $currentFormModel;

    public function __construct(BaseController $controller, 
                                $entity, array $options = array())  
    {
        $this->dataSource = $entity;

        parent::__construct($controller, $options);
    }    
    
    /**
     * @return Form
     */
    public function getForm() 
    {
        if (is_null($this->form)) {
            $this->form = $this->controller->createForm(
                $this->getRootFormType(),
                $this->dataSource,
                $this->options['form_type_options']
            );
        }

        return $this->form;
    }
       
    /**
     * Get a list of forms to save.
     * @return Form[] The forms to be saved
     */
    public function getSaveForms() 
    {
        $form = $this->getForm();
        $saveForms = is_array($this->options['save_forms']) ? 
                 $this->options['save_forms'] : [$this->options['save_forms']];
        $forms = array();
        $this->findFormTypes($form, $saveForms, $forms);
        
        return $forms;
    }
    
    public function getCurrentFormModel()
    {
        return $this->currentFormModel;
    }
                
    private function findFormTypes($rootForm, $formTypes, &$forms) 
    {
        if (in_array($rootForm->getName(), $formTypes)) {            
            $forms[] = $rootForm;
        }
                                
        foreach ($rootForm->all() as $form) {            
            $this->findFormTypes($form, $formTypes, $forms);
        }
    }
    
    /**
     * @see Form::isValid()
     */
    public function isValid() 
    {
        return $this->getForm()->isValid();
    }
    
    /**
     * Class to save the data to all root entities associated to the main 
     * form types of the view
     */
    public function saveData() 
    {        
        $forms = $this->getSaveForms();        
        foreach ($forms as $form) {            
            $data = $form->getData();                        
            $this->riseFormBuilderSaveDataEvent($data, $form);
            if ($data instanceof EntityInterface) {
                $data->save();                
            }
            else if ($data instanceof IteratorAggregate) {                
                foreach($data as $item) {
                    if ($item instanceof EntityInterface) {
                        $item->save();
                    }
                }
            }                            
        }
        
        return $this->isDataSaved = true;
    }  
    
    protected function riseFormBuilderSaveDataEvent($data, $form)
    {
        $event = new FormBuilderSaveDataEvent($data, $form);
        $this->dispatcher->dispatch(FormBuilderEvents::BUILDER_SAVE_DATA, $event);
    }
    
    /**
     * Compute and return the form View that can be passed directly to the template
     * @return FormView
     */
    public function getFormView() 
    {
        return $this->getForm()->createView();
    }

    /**
     * {@inheritDoc}
     */
    public function getViewParameters($prefix = "form", $formVariableName = "form") 
    {                          
        return [
            $formVariableName => $this->getFormView(),
            $prefix . '_template_type' => $this->options['template_type'],
            $prefix . '_theme' => $this->options['theme'],
            $prefix . '_data_saved' => $this->isDataSaved
        ];
    }    

    /**
     * Calls a method to handle the event FormBuilderEvents::PRE_BUILD_FORM.
     * 
     * @param FormBuilderEvent Related event object.
     */
    public function onPreBuildForm(FormBuilderEvent $event) 
    {
        $formName = $event->getForm()->getName();
        $this->callViewEvent('onPreBuildForm', $formName, $event);
    }

    /**
     * Calls a method to handle the event FormBuilderEvents::POST_BUILD_FORM.
     * 
     * @param FormBuilderEvent Related event object.
     */
    public function onPostBuildForm(FormBuilderEvent $event) 
    {
        $formName = $event->getForm()->getName();
        $this->applyConfig($event->getForm());
        $this->callViewEvent('onPostBuildForm', $formName, $event);
    }
    
    /* Calls a method to handle the event FormBuilderEvents::EMPTY_DATA_FORM.
     * 
     * @param LLPFormEvent Related event object.
     */
    public function onEmptyDataForm(FormBuilderEmptyDataEvent $event) 
    {
        $formName = $event->getFormId();
        $this->callViewEvent('onEmptyDataForm', $formName, $event);
    }

    protected function getRootFormType() 
    {
        return $this->options['form_type'];        
    }    
    
    protected function applyConfig(FormNodeInterface $formModel) 
    {
        $this->currentFormModel = $formModel;
        
        $config = $this->getConfig();
        if (!isset($config)) {
            throw new BuilderConfigurationException('Error by accessing the configuration of the form.');
        }
        
        $config->applyConfig();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver) 
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults([
            'form_type_options' => [],
            'template_type' => 'tabs',
            'theme' => 'LooopCoreFrameworkBundle:_form:_defaultFields.html.twig',    
            'save_forms' => [],
            'config_strategy' => 'looopcore.form_builder_config_strategy'
            ]);
        
        $resolver->setRequired(array(
            'form_type', // Root form service id
            'form_type_options',
            'save_forms', // A list of form identifiers to be saved
            'template_type', // Template's type ('tabs', 'table', 'div')
            'theme', // Aditional Twig template for the fields of the form
        ));                
    }

    protected function registerViewEvents() 
    {
        parent::registerViewEvents();

        $this->registerEventListener(FormBuilderEvents::PRE_BUILD_FORM, 
                                            array($this, 'onPreBuildForm'), 1);
        $this->registerEventListener(FormBuilderEvents::POST_BUILD_FORM, 
                                            array($this, 'onPostBuildForm'), 1);
        $this->registerEventListener(FormBuilderEvents::EMPTY_DATA_FORM, 
                                            array($this, 'onEmptyDataForm'), 1);        
    }
}
