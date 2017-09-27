<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GalleryController extends Controller
{
 


    public function logotypesListAction()
    {   
        $logotypes = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:Gallery')
                ->findAllEnabledLogotypes();
                
        return $this->render('ParabolAdminCoreBundle:App/Gallery:logotypesList.html.twig', array(
                'logotypes' => $logotypes,            
            ));    
    }


	
    public function listAction($type)
    {   

        $page = $this->getDoctrine()
                    ->getRepository('ParabolAdminCoreBundle:Page')
                    ->findOneBySlug(substr($this->getRequest()->getPathInfo(),1));

        $galleries = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:Gallery')
                ->findAllEnabledByType($type);
                
        return $this->render('ParabolAdminCoreBundle:App/Gallery:list.html.twig', array(
                'galleries' => $galleries,
                'page' => $page,
            ));    
    }   

}
