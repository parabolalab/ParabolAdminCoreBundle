<?php

namespace Parabol\AdminCoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EntityMinNumber extends Constraint
{
    public $message = 'This collection should contain {{ limit }} element.';
    public $min = 1;

    public function __construct($options = null) //\Doctrine\ORM\EntityManager $em
    {
        parent::__construct($options);
        
        // if (null === $this->fields) {
        //     throw new MissingOptionsException(sprintf('Option "fields" must be given for constraint %s', __CLASS__), array('field'));
        // }
    }

    public function validatedBy()
	{
	    return get_class($this).'Validator';
	}

}