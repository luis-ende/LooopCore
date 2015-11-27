<?php

namespace LooopCore\FrameworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatePickerType extends AbstractType {

    protected $dateFormat;

    public function __construct($dateFormat) {
        $this->dateFormat = $dateFormat;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => $this->dateFormat,
            'attr' => array('class' => 'datepicker')
        ));
    }

    public function getParent() {
        return 'date';
    }

    public function getName() {
        return 'datePicker';
    }

}
