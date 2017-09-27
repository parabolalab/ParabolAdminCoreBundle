<?php

namespace Parabol\AdminCoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtChoiceType extends AbstractType
{
    // public function setDefaultOptions(OptionsResolverInterface $resolver)
    // {
    //     $resolver->setDefaults(array(
     
    //     ));
    // }
    public static $fields = [];
    public static $currentClass = null;
    public static $currentField = null;
    private $choices = [];
    
    public function __construct($entityChoices)
    {

      
      // var_dump($entityChoices, self::$fields);
      // die();

      

       foreach(self::$fields as $field)
       {
           // var_dump($field, $entityChoices, self::$currentClass);
           self::$currentField = $field;

           if(isset($entityChoices[self::$currentClass]) && isset($entityChoices[self::$currentClass][self::$currentField]))
           {
                $this->choices[self::$currentField] = $entityChoices[self::$currentClass][self::$currentField];            
           }
           elseif(method_exists(self::$currentClass, 'default_'.self::$currentField) && $choices = call_user_func(self::$currentClass.'::default_'.self::$currentField))
           {
                $this->choices[self::$currentField] = $choices;
           }


       }


       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
                'choices' => isset($this->choices[self::$currentField]) ? $this->choices[self::$currentField] : [],
                'translation_domain' => 'ext_choice',
        ));
    }

    // public function setDefaultOptions(OptionsResolverInterface $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'choices' => array('aaa'),
    //         'translation_domain' => 'ext_choice',
    //     ));

    // }


    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class;
    }

    public function getName()
    {
        return 'ext_choice';
    }
}