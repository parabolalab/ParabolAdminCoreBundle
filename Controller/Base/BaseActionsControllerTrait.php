<?php

namespace Parabol\AdminCoreBundle\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;

trait BaseActionsControllerTrait {

	public function attemptObjectUpdateDialog($pk)
	{

		$response = $this->forward(preg_replace('#(.*)ActionsController$#', '\\\$1EditController::updateDialog', get_class($this)), array(
       				'pk' => $pk
	    ));

	    return $response;
	}

	public function getObjectRepositiory($obj)
	{
		$class = get_class($obj);

		return $this->getDoctrine()
             ->getManagerForClass($class)
             ->getRepository($class);
	}

	public function attemptObjectReorder($pk)
	{
		$sort = $this->get('request_stack')->getCurrentRequest()->get('sort');
		$obj = $this->getObject($pk);


		$obj->setSort($sort);		

		$em = $this->getDoctrine()->getManager();
		$em->flush();


		// throw new \Exception("Error Processing Request", 1);
		
		// $rep = $this->getObjectRepositiory($obj);

		// $rep->reorder($obj, $sort);
		
		return new JsonResponse(array('result' => 'success'));
	}
}