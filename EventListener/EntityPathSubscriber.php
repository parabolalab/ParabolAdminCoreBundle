<?php

namespace Parabol\AdminCoreBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Parabol\AdminCoreBundle\Entity\Path;
use Parabol\AdminCoreBundle\Entity\PathTransation;
use Parabol\BaseBundle\Util\PathUtil;

class EntityPathSubscriber implements EventSubscriber
{
	private $router, $locales;

	public function __construct($router, $locales)
	{
	    $this->router = $router;
	    $this->locales = $locales;
	}

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->saveUrl($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->saveUrl($args);
    }

    public function saveUrl(LifecycleEventArgs $args)
    {
   //      $entity = $args->getEntity();
   //      $em = $args->getEntityManager();

   //      if(!$entity instanceof Path && !$entity instanceof PathTransaltion)
   //      {
   //      	$class = get_class($entity);

   //      	$route_name = PathUtil::generateRouteName($class, 'app');
			// $route = $this->router->getRouteCollection()->get($route_name);

			
			// $path = new Path();
   //      	$path->setClass($class);
   //      	$path->setObjectId($entity->getId());


        	
   //      	foreach($this->locales as $locale)
			// {
			// 	$local_route = $this->router->getRouteCollection()->get($locale.'__RG__'.$route_name);
			// 	if($local_route) $route = $local_route;
			// 	$_path = $route->getPath();	
	        	
	  //       	foreach($route->compile()->getPathVariables() as $var)
			// 	{
			// 		$method = 'get'.ucfirst($var);
			// 		$_path = strtr($_path, array( '{'.$var.'}' =>  $entity->translate($locale)->$method()));
			// 	}

			// 	$path->translate($locale)->setUrl($_path);

				
			// }

			// $em->persist($path);
			// $path->mergeNewTranslations();

			// $em->flush();

   //      }

        
    }

}