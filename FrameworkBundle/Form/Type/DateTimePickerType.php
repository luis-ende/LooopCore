<?php

namespace LooopCore\FrameworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateTimePickerType extends AbstractType {
    
    protected $dateFormat;

    public function __construct($dateFormat) {
        $this->dateFormat = $dateFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $dateOptions = array_merge($builder->get('date')->getOptions(), 
                array(
                    'attr' => array('class' => 'datepicker'),
                    'format' => $this->dateFormat,
                    ));

        $builder->remove('date')
                ->add('date', 'datePicker', $dateOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() {
        return 'datetime';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'date_widget' => 'single_text',
            'time_widget' => 'choice',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'dateTimePicker';
    }

}