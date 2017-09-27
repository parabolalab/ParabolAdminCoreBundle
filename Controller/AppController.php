<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{

	public function callerAction($action)
	{
		return $this->{$action.'Action'}();
	}

	public function sendEmailAction()
	{

		$request = $this->get('request_stack')->getCurrentRequest();
		$ns = $request->get('ns');

		if(!$ns) throw new Exception("ns request parameter is required.");
		

		$values = $request->get($ns);

		$message = \Swift_Message::newInstance()
	        ->setSubject(
	        		$this->container->hasParameter($ns . '_form.title') ? 
	        					$this->container->getParameter($ns . '_form.title') : 
	        						(
	        							'[Formularz]' . (isset($values['subject']) ? $values['subject'] : 'Wiadomość z formularza' )
	        						)
	        )
	        ->setFrom('formularz@' .  str_replace('www.', '', $request->getHost()))
	        ->setTo($this->container->getParameter($ns . '_form.email'))
	        ->setBody(
	            // $this->renderView(
	            //     // app/Resources/views/Emails/registration.html.twig
	            //     'Emails/registration.html.twig',
	            //     array('name' => $name)
	            // )
	            (isset($values['name']) ? 'od: '.$values['name'].'<br />' : '').
	            (isset($values['subject']) ? 'temat: '.$values['subject'].'<br />' : '').
	            'email: '.$values['email'].'<br /><br />'.
	            ''.$values['message'].'<br />',
	            'text/html'
	        )

	    ;

	    
	    
	    $message->setReplyTo($values['email']);

    	$status = $this->get('mailer')->send($message);

    	$lc = $request->getLocale();

    	if($status)
    	{
    		if($this->container->hasParameter($ns . '_form.success_message.'.$lc))
	    	{
	    		$message = $this->container->getParameter($ns . '_form.success_message.'.$lc);
	    	}
	    	elseif($this->container->hasParameter($ns . '_form.success_message'))
	    	{
	    		$message = $this->container->getParameter($ns . '_form.success_message');
	    	}
	    	else {
	    		$message = $this->get('translator')->trans('Massage has been sent, thank you.');
	    	}	
    	}
    	else
    	{

    		if($this->container->hasParameter($ns . '_form.error_message.'.$lc))
	    	{
	    		$message = $this->container->getParameter($ns . '_form.error_message.'.$lc);
	    	}
	    	elseif($this->container->hasParameter($ns . '_form.error_message'))
	    	{
	    		$message = $this->container->getParameter($ns . '_form.error_message');
	    	}
	    	else {
	    		$message = $this->get('translator')->trans('An error occurred while sending the message.');
	    	}
    	}

    	
    	
    	
		return new Response($message);
	}


	public function sendTestEmailAction()
	{

		$message = \Swift_Message::newInstance()
	        ->setSubject($this->container->getParameter('contact_form.title'))
	        ->setFrom('formularz@' .  str_replace('www.', '', $this->get('request_stack')->getCurrentRequest()->getHost()))
	        ->setTo($this->container->getParameter('contact_form.email'))
	        ->setBody(
	            // $this->renderView(
	            //     // app/Resources/views/Emails/registration.html.twig
	            //     'Emails/registration.html.twig',
	            //     array('name' => $name)
	            // )
	            'Wiadomość z akcji sprawdzającej konfiguracje mailera<br />',
	            'text/html'
	        )

	    ;

	    
	    
	
    	$status = $this->get('mailer')->send($message);

    	// throw new \Exception("Error Processing Request", 1);
    	
		return new Response('<h4 class="msg-success">Wiadomość została wysłana.</h4>');
	}

	public function showEmailConfigAction()
	{
		return new Response($this->container->getParameter('contact_form.email'));
	}

	public function renderCodeAction(Request $request, $pathInfo, $type)
	{
		$qb = $this->getDoctrine()->getRepository('ParabolAdminCoreBundle:Code')->createQueryBuilder('c');



		$url_part = explode('/', $pathInfo);

        if(isset($url_part[1]) && $url_part[1] == '') $url_part = [''];

        $params = array();
        $last = '';
        foreach($url_part as $i => $part)
        {
        	$params[':url'.$i] = ($last != '/' ? $last : '') . '/'.$part;
        	$last = $params[':url'.$i];
        	if(isset($url_part[$i+1])) $qb->orWhere('c.inherited = 1 AND c.url LIKE :url'.$i);
        	else $qb->orWhere('c.url = :url'.$i);
        }
        $qb
        	->orderBy('c.url', 'DESC')
        	->addOrderBy('c.inherited', 'ASC')
        	->setParameters($params)
        	;
        try
        {
            $codes = $qb->getQuery()->getResult();    
        }
        catch(NoResultException $e)
        {
            $codes = null;
        }
        $metatags = array();

        if(isset($codes[0]))
        {
            foreach($codes as $code)
            {
                $result = $code->{'get' . ucfirst($type)}();
                if($result)
                {
                	return new Response(strtr($result, ['${locale}' => $request->getLocale()]));
                	break;
                }
            }
	    }

	    return new Response();

	}
}
