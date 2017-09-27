<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TextBlockController extends Controller
{

    public function showAction()
    {
    	$blocks = $this->getDoctrine()
		        ->getRepository('ParabolAdminCoreBundle:TextBlock')
		        ->createQueryBuilder('b')
                ->leftJoin('b.translations', 't')
                ->where("t.displayOnUrl = :url OR t.displayOnUrl IS NULL")
                ->andWhere("b.type IN ('" . implode("','", $this->getRequest()->get('types')) . "')")
                ->addOrderBy('b.sort', 'ASC')
                ->setParameters(
                    array(
                        ':url' => $this->getRequest()->get('path_info'), 
                        )
                    )
                ->getQuery()
                ->getResult();

        return $this->render('ParabolAdminCoreBundle:App/TextBlock:_show.html.twig', array(
                'textblocks' => $blocks
        ));    
    }


    public function showCustomAction()
    {
        $blocks = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:TextBlock')
                ->createQueryBuilder('b')
                ->leftJoin('b.translations', 't')
                ->where("t.displayOnUrl = :url OR t.displayOnUrl IS NULL")
                ->andWhere("b.type IN ('custom')")
                ->addOrderBy('b.sort', 'ASC')
                ->setParameters(
                    array(
                        ':url' => $this->getRequest()->get('path_info'), 
                        )
                    )
                ->getQuery()
                ->getResult();

        return $this->render('ParabolAdminCoreBundle:App/TextBlock:_showCustom.html.twig', array(
                'textblocks' => $blocks
        ));    
    }

}
