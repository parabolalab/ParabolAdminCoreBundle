<?php

namespace Parabol\AdminCoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GoogleLocationMapType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
     
        ));
    }

    // public function getParent()
    // {
    //     return 'choice';
    // }

    public function getName()
    {
        return 'google_location_map';
    }
}