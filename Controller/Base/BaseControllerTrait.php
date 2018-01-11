<?php

namespace Parabol\AdminCoreBundle\Controller\Base;

trait BaseControllerTrait {

	// protected function getNewType()
 //    {
 //        return $this->getType();    
 //    }

 //    protected function getEditType()
 //    {
 //        return $this->getType();
 //    }

 //    protected function getType()
 //    {
 //        $class = get_class($this);
 //        $typeClass = '\\'.strtr($class, array('EditController'=>'EditType','NewController'=>'NewType', 'Controller' => 'Form\Type'));
        
 //        $type = new $typeClass();

 //        $type->setSecurityContext($this->get('security.context'));

 //        $type->setExtChoiceOptions($this->container->getParameter('ext_choice.choices', array()));
        
        
 //        return $type;
 //    }

	protected function createForm($type, $data = null, array $options = array())
    {

    	if(strpos($type, 'Filters') == false)
    		$options['builderExtensions'] = $this->container->get('parabol.admin_base_type_extension');

        return $this->container->get('form.factory')->create($type, $data, $options);
    }
}