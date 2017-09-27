<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function showAction(Request $request)
    {
        $page = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:Page')
                ->createQueryBuilder('p')
                ->leftJoin('p.translations', 't')
                ->where('t.slug = :slug')
                ->setParameters(array(':slug' => $request->get('slug')))
                ->getQuery()
                ->getSingleResult();



        if(property_exists($page, 'type') && $this->get('templating')->exists('ParabolAdminCoreBundle:App/Page:'.$page->getType().'.html.twig'))
        {
            return $this->render('ParabolAdminCoreBundle:App/Page:'.$page->getType().'.html.twig', array(
                    'page' => $page
            ));
        }
        else
        {
            return $this->render('ParabolAdminCoreBundle:App/Page:show.html.twig', array(
                    'page' => $page
            ));    
        }
    }


    public function homepageAction(Request $request, $page = null, $slide = null)
    {

        if($page)
        {
            try
            {
                $currentType = $this->getDoctrine()
                    ->getRepository('ParabolAdminCoreBundle:Page')
                    ->createQueryBuilder('p')
                    ->select('p.type')
                    ->leftJoin('p.translations', 't')
                    ->where('t.slug = :slug')
                    ->setParameters(array(':slug' => $page))
                    ->getQuery()
                    ->getSingleScalarResult(); 
            }
            catch(\Doctrine\ORM\NoResultException $e)
            {
                return $this->createNotFoundException();
            }

            if($currentType == 'homepage')
            {
                return $this->redirectToRoute('homepage');
            }
          
        }


      
        $page = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:Page')
                ->findOneByType('homepage');

        
        return $this->render('ParabolAdminCoreBundle:App/Page:homepage.html.twig', array(
                'page' => $page,
            ));    
    }

    public function navAction()
    {
        // var_dump('homepage');
        $pages = $this->getDoctrine()
                ->getRepository('ParabolAdminCoreBundle:Page')
                ->createQueryBuilder('p')
                ->leftJoin('p.translations', 't')
                ->select('p.id, t.title, t.slug, p.type')
                ->where('p.inMenu = :inmenu')
                ->setParameter('inmenu', true)
                ->orderBy('p.sort', 'asc')
                ->getQuery()
                ->execute();

        
        return $this->render('ParabolAdminCoreBundle:App/Page:_nav.html.twig', array(
                'pages' => $pages
                // 'page' => $page[0]
            ));    
    }

    public function testAction(Request $request)
    {
            // var_dump('test');
            // var_dump(get_class($this->getDoctrine()));
            $page = $this->getDoctrine()
                ->getRepository('AppBundle:Foo')
                ->findOneBy(array('id' => 2));

            // $page = $this->getDoctrine()
            //     ->getRepository('AppBundle:Foo')
            //     ->find(2);
            return new Response($page->getContent() . $request->query->get('dev'));
    }

}
