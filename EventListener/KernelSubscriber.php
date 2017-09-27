<?php

namespace Parabol\AdminCoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

class KernelSubscriber implements EventSubscriberInterface {

	public function __construct($container)
	{
		$this->container = $container;
	}

	public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('onKernelRequest', 0)
            )
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

       // if($event->getRequest()->getLocale() == 'pl') {
       //  var_dump($event->getRequest()->getLocale());
        
       //   }

       //   var_dump($this->container->get('session')->get('defaultLocale'));

       //   die();
        // var_dump(array($event->getRequest()->getPathInfo() => preg_match('#^(\/admin\/)#', $event->getRequest()->getPathInfo())))

        // if(!$this->container->get('session')->get('countryName')) $this->container->get('session')->set('countryName', 'International Website');

        if(preg_match('#^\/admin\/#', $event->getRequest()->getPathInfo()))
        {
            if( $event->getRequest()->getLocale() != 'pl' && preg_match('#^(\/admin\/)#', $event->getRequest()->getPathInfo()))
            {
                $event->getRequest()->setLocale('pl');
                $event->setResponse(new RedirectResponse('/app_dev.php' . $event->getRequest()->getPathInfo()));
            } 
            
        }
        // elseif($this->container->get('session')->get('defaultLocale'))
        // {
        //     if($this->container->get('session')->get('countryName', 'International Website') != 'International Website' || $event->getRequest()->getLocale() != 'pl')
        //     {
        //         if($event->getRequest()->getLocale() != $this->container->get('session')->get('defaultLocale') && 'en' != $event->getRequest()->getLocale() )
        //         {
        //             $event->getRequest()->setLocale($this->container->get('session')->get('defaultLocale'));
        //         }
        //     }
        // }

    }

}