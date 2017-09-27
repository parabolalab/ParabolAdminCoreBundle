<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{

    public function showListAction()
    {
        $menu = $this->getRequest()->get('menu');
    	$menuItems = $this->getDoctrine()
		        ->getRepository('ParabolAdminCoreBundle:MenuItem')
		        ->createQueryBuilder('mi')
                ->select('mi, mic')
                ->leftJoin('mi.menu', 'm')
                ->leftJoin('mi.children', 'mic', 'WITH', 'mic.isEnabled = 1')
                ->where('m.name = :menu')
                ->andWhere("mi.isEnabled = 1")
                ->andWhere("mi.parent IS NULL")
                ->andWhere("mi.displayOnUrl = :url OR mi.displayOnUrl IS NULL")
                ->andWhere("mi.type IN ('list', 'box_and_list')")
                ->addOrderBy('mi.sort', 'ASC')
                ->addOrderBy('mic.sort', 'ASC')
                ->setParameters(
                    array(  
                        ':url' => $this->getRequest()->get('path_info'), 
                        ':menu' => $menu, 
                        )
                    )
                ->getQuery()
                ->getResult();

        return $this->render('ParabolAdminCoreBundle:App/Menu:_showList.html.twig', array(
                'menuItems' => $menuItems,
                'menu' => $menu,
                'path_info' => $this->getRequest()->get('path_info')
            ));    
    }

    public function showBoxAction()
    {
        $menu =  $this->getRequest()->get('menu');
        $menuItems = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:MenuItem')
                ->createQueryBuilder('mi')
                ->leftJoin('mi.menu', 'm')
                ->where('m.name = :menu')
                ->andWhere("mi.isEnabled = 1")
                ->andWhere("mi.displayOnUrl = :url OR mi.displayOnUrl IS NULL")
                ->andWhere("mi.type IN ('box', 'list_and_box')")
                ->addOrderBy('mi.sort', 'ASC')
                ->setParameters(
                    array(
                        ':url' => $this->getRequest()->get('path_info'), 
                        ':menu' => $menu, 
                        )
                    )
                ->getQuery()
                ->getResult();

        return $this->render('ParabolAdminCoreBundle:App/Menu:_showBox.html.twig', array(
                'menuItems' => $menuItems,
                'menu' => $menu,
                'class' => $this->getRequest()->get('class'),
                'path_info' => $this->getRequest()->get('path_info')
            ));    
    }


    public function showSimpleListAction()
    {
        $menu = $this->getRequest()->get('menu');
        $menuItems = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:MenuItem')
                ->createQueryBuilder('mi')
                ->select('mi')
                ->leftJoin('mi.menu', 'm')
                ->where('m.name = :menu')
                ->andWhere("mi.isEnabled = 1")
                ->andWhere("mi.displayOnUrl = :url OR mi.displayOnUrl IS NULL")
                ->andWhere("mi.type IN ('list', 'box_and_list')")
                ->addOrderBy('mi.sort', 'ASC')
                ->setParameters(
                    array(  
                        ':url' => $this->getRequest()->get('path_info'), 
                        ':menu' => $menu, 
                        )
                    )
                ->getQuery()
                ->getResult();

        return $this->render('ParabolAdminCoreBundle:App/Menu:_showSimpleList.html.twig', array(
                'menuItems' => $menuItems,
                'path_info' => $this->getRequest()->get('path_info'),
                'separator' => $this->getRequest()->get('separator')
            ));    
    }
    

}
