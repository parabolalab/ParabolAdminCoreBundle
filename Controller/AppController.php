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

	private function validateReCaptcha($token)
	{

		if($this->container->hasParameter('recaptcha.secret'))
		{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
			            "secret=" . $this->container->getParameter('recaptcha.secret') . "&response=$token");

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);

			curl_close ($ch);

			$result = json_decode($server_output, true);

			return isset($result['success']) && $result['success'];

		}

		return true;
	}

	public function sendEmailAction()
	{

		$request = $this->get('request_stack')->getCurrentRequest();
		$ns = $request->get('ns');

		if(!$ns) throw new Exception("ns request parameter is required.");

		$captchaChalenge = $this->validateReCaptcha($request->get('g-recaptcha-response'));
		

		if(!$captchaChalenge)
		{
			$result = 'error';
		}
		else
		{

			$values = $request->get($ns);

			$template = ''; 

			if($this->get('templating')->exists('emails/' . $ns . '.html.twig'))
			{
				$template = 'emails/' . $ns . '.html.twig';
			}
			else
			{
				$template = 'emails/default.html.twig';
			}

			
			$body = $this->renderView(
		                $template,
		                ['vars' => $values]
		            );

			$message = \Swift_Message::newInstance()
		        ->setSubject(
		        		$this->container->hasParameter($ns . '.title') ? 
		        					$this->container->getParameter($ns . '.title') : 
		        						(
		        							'[Formularz]' . (isset($values['subject']) ? $values['subject'] : 'Wiadomość z formularza' )
		        						)
		        )
		        ->setFrom( $this->container->hasParameter($ns.'.email') ? $this->container->getParameter($ns.'.email') : 'formularz@' .  str_replace('www.', '', $request->getHost()) )
		        ->setTo($this->container->getParameter($ns . '.email'))
		        ->setBody(
		            $body,
		            'text/html'
		        )
		    ;


		    if($request->files->has($ns))
		    {
			    foreach($request->files->get($ns) as $name => $files)
				{
					foreach($files as $file)
					{
						if($file->getPath())
						{
							$message->attach(
							  \Swift_Attachment::fromPath($file->getPathname())->setFilename($file->getClientOriginalName())
							);
						}
					}
				}
			}

			if($values['email']) $message->setReplyTo($values['email']);

	    	$status = $this->get('mailer')->send($message);

	    	$result = $status ? 'success' : 'error';
	    }

    	
    	$locale = $request->getLocale();
    	$message = 'missing ' . $ns . '.' . $result . '_message parameter';
        if($this->container->hasParameter($ns . '.' . $result . '_message'))
        {

        	$message = '';
            $trans = $this->container->getParameter($ns . '.' . $result . '_message');
            
            if(is_array($trans))
            {
                if(isset($trans[$locale]))
                {
                    $message = $trans[$locale];
                }
            }
            else $message = $trans;

        }

    	
    	if($request->isXmlHttpRequest())
    	{
    		return new Response($message);
    	}
    	else
    	{
    		$this->addFlash($result, $message);
    		return $this->redirect($request->headers->get('referer'));
    	}
    	
		
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
		$qb = $this->getDoctrine()->getRepository('AppAdminCoreBundle:Code')->createQueryBuilder('c');



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
