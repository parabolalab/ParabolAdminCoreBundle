<?php

namespace Parabol\AdminCoreBundle\Controller\BaseAdmin\Page;

use Admingenerated\AppAdminCoreBundle\BasePageController\ActionsController as BaseActionsController;
use App\AdminCoreBundle\Entity\Page;
/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
	use \Parabol\AdminCoreBundle\Controller\Base\BaseControllerTrait;
	use \Parabol\AdminCoreBundle\Controller\Base\BaseActionsControllerTrait;

	protected function executeObjectDelete(Page $Page)
    {
    	if(!in_array($Page->getType(), Page::undeletableTypes()))
    	{
    		parent::executeObjectDelete($Page);
    	}
    	else
    	{
    		$this->get('session')->getFlashBag()->add(
	            'error',
	            $this->get('translator')->trans(
	                "Nie można usunąć tej strony",
	                array(),
	                'Admingenerator'
	            )
        	);

        	throw new \Exception('Removing of specific page types is disallowed.', 6000);
    	}
    	
    }

    protected function errorObjectDelete(\Exception $e, Page $Page = null)
    {

    	if($e->getCode() !== 6000)
    	{
        	return parent::errorObjectDelete($e, $Page);
        }
        else
        {
        	return $this->redirect($this->generateUrl("App_AdminCoreBundle_Page_list"));
        }
        
    }
}
