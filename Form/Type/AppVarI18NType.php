<?php

namespace Parabol\AdminCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppVarI18NType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translations',  \A2lix\TranslationFormBundle\Form\Type\TranslationsType::class, [
                'label' => ' ',
                'locales' => ['pl', 'en'],
                'default_locale' => ['pl'],
                'required_locales' => [],
                'fields' => [
                    'value' => [
                        'label' => ' ',
                        'field_type' => $options['fieldType'],
                        'constraints' => $options['constraints'], 
                    ]
                ],
                'excluded_fields' => [
                    'namespaceLabel',
                    'varLabel',
                    'help'
                ]
        ]);
    }

     public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
            'fieldType' => null
         ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }
}