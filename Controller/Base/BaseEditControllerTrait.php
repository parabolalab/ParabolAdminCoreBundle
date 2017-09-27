<?php

namespace Parabol\AdminCoreBundle\Controller\Base;

trait BaseEditControllerTrait {

	public function updateDialog($pk)
	{
		$obj = $this->getObject($pk);

        if (!$obj) {
            throw new NotFoundHttpException("The ".get_class($this->getObject)." with id $pk can't be found");
        }



        $this->preBindRequest($obj);
        $form = $this->getEditForm($obj);
        $form->handleRequest($this->get('request_stack')->getCurrentRequest());
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
       

        return $this->render('ParabolAdminCoreBundle:Admin/Dialog:_dialogForm.html.twig', array(
    		'form' => $form->createView(),
    		'title' => '',
            'formParams' => [ (new \ReflectionClass($obj))->getShortName() => $obj ],
  		
	    ));  
	}
}