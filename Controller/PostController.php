<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{

    public function latestAction(Request $request)
    {
        $type = $request->get('type', 'news');

        $post = $this->getDoctrine()
                    ->getRepository('ParabolAdminCoreBundle:Post')
                    ->findLastByTypeAndLocale($type, $request->getLocale());

        return $this->render('ParabolAdminCoreBundle:App/Post:_latest.html.twig', array(
                'post' => $post,
                'type' => $type
            ));    
    }


    public function showAction(Request $request)
    {
        $type = $request->get('type', 'news');

        $post = $this->getDoctrine()
                    ->getRepository('ParabolAdminCoreBundle:Post')
                    ->findOne($request->get('slug'), $type, $request->getLocale());

        return $this->render('ParabolAdminCoreBundle:App/Post:show.html.twig', array(
                'post' => $post,
                'type' => $type
            ));    
    }


    public function listAction(Request $request)
    {   
        if($request->isXMLHttpRequest())
        {
            return $this->boxListAction($request);  
        } 

        $type = $request->get('type', 'news');
        $max = $request->get('max', 12);
        $page = $request->get('page', 1);


        $query = $this->getDoctrine()
            ->getRepository('ParabolAdminCoreBundle:Post')
            ->allByTypeAndLocale($type, $request->getLocale())
            ->setMaxResults($max)
            ->setFirstResult($max * ($page - 1))
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = true);    


                    
       
        return $this->render('ParabolAdminCoreBundle:App/Post:list.html.twig', array(
                'paginator' => $paginator,
                'type' => $type,            
            ));    
    }

    public function boxListAction(Request $request)
    {   
        $type = $request->get('type', 'news');
        $max = $request->get('max', 12);
        $page = $request->get('page', 1);

        $query = $this->getDoctrine()
            ->getRepository('ParabolAdminCoreBundle:Post')
            ->allByTypeAndLocale($type, $request->getLocale())
            ->setMaxResults($max)
            ->setFirstResult($max * ($page - 1))
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = true);    

        $partial = '_boxList';
        if($request->get('is_sidebar')) $partial .= 'Sidebar';
        elseif(!$request->isXMLHttpRequest()) $partial .= 'WithPager';
        
        return $this->render('ParabolAdminCoreBundle:App/Post:'.$partial.'.html.twig', array(
                'paginator'  => $paginator ,
                'type' => $type, 
                'page' => $page,
                'maxPage' => ceil($paginator->count()/$max),         
            ));    
        
    }

}
