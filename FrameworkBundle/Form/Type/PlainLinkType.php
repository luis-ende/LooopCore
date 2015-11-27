<?php

namespace LooopCore\FrameworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlainLinkType extends AbstractType
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
            'link_params' => array()
        ));
        $resolver->setRequired(array('link_label', 'link_route'));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['link_label'] = $options['link_label'];
        $view->vars['link_route'] = $options['link_route'];
        $view->vars['link_params'] = $options['link_params'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'plain_link';
    }
}
