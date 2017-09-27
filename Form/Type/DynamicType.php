<?php

namespace Parabol\AdminCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $labels = ['name' => 'Nazwa', 'from' => 'Od', 'to' => 'Do', 'value' => 'Wartość'];
        foreach($options['fields'] as $name => $type)
        {
        	$builder->add($name, \Parabol\AdminCoreBundle\Model\AppVar::formType($type), array('label' =>  isset($labels[$name]) ? $labels[$name] : $name,'constraints' => isset($options['fields_constraints'][$name]) ? $options['fields_constraints'][$name] : null));
        }
    }

     public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
                'fields' => array(),
                'fields_constraints' => array(),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr'         => array('class' => 'xxx'),
        ));
    }
}