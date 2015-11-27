<?php

namespace LooopCore\FrameworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use APY\DataGridBundle\Grid\Grid;

class DataGridType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget'  => 'single_text',            
            'read_only' => true,            
            'disabled' => true,            
            'attr' => array('class' => $this->getName()),
            'compound' => false,
            'mapped' => false,            
            'label' => ' '
        ));
        $resolver->setRequired(array('grid', 'grid_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {        
        /** @var $grid Grid */
        $grid = $options['grid'];                        
        
        $view->vars['grid'] = $grid;
        $view->vars['grid_template'] = $options['grid_template'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'data_grid';
    }
}
