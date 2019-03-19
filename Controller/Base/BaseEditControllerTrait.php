<?php

namespace Parabol\AdminCoreBundle\Controller\Base;

trait BaseEditControllerTrait {

	public function updateDialog($pk)
	{
		$obj = $this->getObject($pk);

        if (!$obj) {
            throw new NotFoundHttpException("The ".get_class($this->getObject)." with id $pk can't be found");
        }

        $request = $this->get('request_stack')->getCurrentRequest();

        $this->preBindRequest($obj);
        $form = $this->getEditForm($obj);
        $form->handleRequest($request);
        $this->postBindRequest($form, $obj);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                
                $this->preSave($form, $obj);
                $this->saveObject($obj);
                $this->postSave($form, $obj);

                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans("action.object.edit.success", array(), 'Admingenerator') );

            } catch (\Exception $e) {
                $logger = $this->get('logger')->error($e->getMessage());
                $this->get('session')->getFlashBag()->add('error',  $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator') );
                $this->onException($e, $form, $obj);
            }
        }
	    else 
	    {
	            $this->get('session')->getFlashBag()->add('error',  $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator') );
	    }

        
        $shortName = (new \ReflectionClass($obj))->getShortName();
        return $this->render('ParabolAdminCoreBundle:Admin/Dialog:_dialogForm.html.twig', array(
    		'form' => $form->createView(),
    		'title' => '',
            'formParams' => [ $shortName => $obj ],
            'formTemplate' => preg_replace('/([a-z]+Bundle).*/','$1',strtr($this->getEditType(), ['\\' => ''])) . ':' . $shortName . 'Edit:form.html.twig'
  		
	    ));  
	}
}