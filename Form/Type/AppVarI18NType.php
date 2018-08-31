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
        $translationsOptions = [
                'label' => ' ',
                'fields' => [
                    'value' => [
                        'label' => ' ',
                        'field_type' => $options['fieldType'],
                        'constraints' => $options['constraints'], 
                    ]
                ],
                'exclude_fields' => [
                    'namespaceLabel',
                    'varLabel',
                    'help'
                ]
        ];

        if($options['locales']) $translationsOptions['locales'] = $options['locales'];
        if($options['default_locale']) $translationsOptions['default_locale'] = $options['default_locale'];
        if($options['required_locales']) $translationsOptions['required_locales'] = $options['required_locales'];

        $builder->add('translations',  \A2lix\TranslationFormBundle\Form\Type\TranslationsType::class, $translationsOptions);
    }

     public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
            'fieldType' => null,
            'locales' => null,
            'default_locale' => null,
            'required_locales' => null,
         ));
    }


}