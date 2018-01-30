<?php

namespace Parabol\AdminCoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\NoResultException as NoResultException;

class SeoRequestListener
{

	private $container, $em, $metatag, $id = null;

	public function __construct($container, $em, $metatag)
	{
		$this->container = $container;
		$this->em = $em;
		$this->metatag = $metatag;
	}

    public function onKernelRequest(GetResponseEvent $event)
    {
        if(!class_exists('\App\AdminCoreBundle\Entity\Seo')) return;



        if (!$event->isMasterRequest()) return;
        
        $path = $event->getRequest()->getPathInfo();

        if(strpos($path, '/admin/') === 0) $event->getRequest()->setLocale('pl');

        $url_part = explode('/', $path);


        if(isset($url_part[1]))
        {
            if($url_part[1] == '') $url_part = [''];
            elseif((in_array($url_part[1], array('admin')) || substr($url_part[1],0,1) == '_')) return;
        }

		$web = $this->container->getParameter('kernel.root_dir').'/../web';


		if($path != '/' && file_exists($web.$path)) return;

        $qb = $this->em->getRepository('AppAdminCoreBundle:Seo')->createQueryBuilder('s');

        $params = array();
        foreach($url_part as $i => $part)
        {
        	$params[':url'.$i] = ($i > 1 ? $params[':url'.($i - 1)] : '' ) . '/'.$part;
        	if(isset($url_part[$i+1])) $qb->orWhere('s.inherited = 1 AND s.url LIKE :url'.$i);
        	else $qb->orWhere('s.url = :url'.$i);
        }
        $qb
        	->orderBy('s.url', 'DESC')
            ->setMaxResults(1)
        	->setParameters($params)
        	;
        
        try
        {
            $seo = $qb->getQuery()->getSingleResult();    
        }
        catch(NoResultException $e)
        {
            $seo = null;
        }
        $metatags = array();

        if($seo)
        {
            foreach($seo::$valueMethods as $name => $method) $this->metatag->addMetatag($name, $seo->$method());
	    }
        else
        {
            $this->metatag->addMetatag('title', $this->container->getParameter('portal.name'));
        }
	    
    }

    
}