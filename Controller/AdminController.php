<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Symfony\Component\HttpFoundation\JsonResponse;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Filesystem\Filesystem;


class AdminController extends Controller
{

    public function actionAction(Request $request, $action)
    {
        if(method_exists($this, $action . 'Action'))
        {
            return $this->{$action . 'Action'}($request);
        }
        else throw new \Exception("Action " . $action . " not exists in " . __FILE__);
        
    }

    public function dialogFormAction(Request $request)
    {
    	$id = $request->get('id');
    	$entityClass = $request->get('entity');
    	$entity = null;

    	if($id)
    	{
    		$entity = $this->getDoctrine()->getRepository($entityClass)->find($id);
    		$title = $this->get('translator')->trans($request->get('title', "You're editing: \"%object%\""), ['%object%' => (string)$entity]);
    	}

    	if(!$entity)
    	{
    		
    		$entity = new $entityClass();
    		$title = '';
    	} 

        
    	$form = $this->createForm($request->get('formClass'), $entity, [
		    'action' => $request->get('formAction'),
		    'method' => 'POST',
		]);

        // $this->generateUrl('target_route');
        $shortName = (new \ReflectionClass($entity))->getShortName();
    	return $this->render('ParabolAdminCoreBundle:Admin/Dialog:_dialogForm.html.twig', [
    		'form' => $form->createView(),
    		'title' => $title,
            'formParams' => [ $shortName => $entity ],
            'formTemplate' => preg_replace('/([a-z]+Bundle).*/','$1',strtr($request->get('formClass'), ['\\' => ''])) . ':' . $shortName . 'Edit:form.html.twig'
	    ]);    
    }


    public function checkUrlAction(Request $request)
    {
        $url = $request->get('url');
        if($url[0] == '/') $url = 'http://'.$request->getHost().$url;

        $file_headers = @get_headers($url);

        $status = false;
        if($file_headers != false && !in_array(preg_replace('#^HTTP\/[\d]\.[\d]+ #', '', $file_headers[0]), ['500 Internal Server Error', '404 Not Found'])) $status = true;
        
        return new JsonResponse(['result' => $status, 'id' => $request->get('id') ,'url' => $url]);
    }

    public function customFileAction(Request $request, $type, $filename)
    {
        $dir = $this->get('parabol.utils.path')->getWebDir() . '/' . $type . '/';
        if(!file_exists($dir)) mkdir($dir, 0777, true);

        if(!file_exists($dir . $filename)) file_put_contents($dir . $filename, '');
        $content = file_get_contents($dir . $filename);

        switch($type)
        {
            case 'css':
                $mode = 'css';
            break;
            case 'js':
                $mode = 'javascript';
            break;
            default:
                $mode = 'htmlmixed';
        }
        

        $form = $this->createFormBuilder(['content' => $content])
            ->add('content', \Ivory\CKEditorBundle\Form\Type\CKEditorType::class, [
                'config' => [
                                'height' => '700px', 
                                'startupMode' => 'source',
                                'toolbar' => 'Custom', 
                                'toolbar_Custom' => [],
                                'extraPlugins' => 'codemirror',
                                'codemirror' => [
                                    'mode' => $mode
                                ]
                            ],
                'plugins' => [
                                'codemirror' => [
                                    'path'     => '/bundles/paraboladmincore/js/admin/ckeditor/plugins/codemirror/',
                                    'filename' => 'plugin.js',
                                ],
                                // 'paraboltest' => [
                                //     'path'     => '/bundles/paraboladmincore/js/admin/ckeditor/plugins/paraboltest/',
                                //     'filename' => 'plugin.js',
                                // ],
                            ],
            ])
            ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array('label' => 'Save'))
            ->getForm();


        if($request->isMethod(Request::METHOD_POST))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                file_put_contents($dir . $filename, $form->getData()['content']);

                $process = new Process('../bin/console assetic:dump --env=prod');
                $process->run();

                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans("action.object.edit.success", array(), 'Admingenerator') );

                return $this->redirect($request->getUri());
            }
            else $this->get('session')->getFlashBag()->add('error',  $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator') );
        }

        return $this->render('ParabolAdminCoreBundle:Admin:customFile.html.twig', array(
             'form' => $form->createView()  
        )); 

    }


    public function clearCacheAction(Request $request)
    {
      //   var_dump($this->container->getParameter('kernel.cache_dir'));
      // die();
        // $fs = new Filesystem();
        // $fs->remove($this->container->getParameter('kernel.cache_dir'));


        $cachedFiles = $this->container->getParameter('kernel.root_dir').'/cache/prod/appProdUrl*';

        foreach (glob($cachedFiles) as $file) {
             unlink($file);
        }

        $containerCache = $this->container->getParameter('kernel.root_dir').'/cache/prod/appProdProjectContainer.php';
        if(file_exists($containerCache)) unlink($containerCache);

        if($locales)
        {
            foreach((array)$locales as $locale)
            {

                $cachedFiles = $this->container->getParameter('kernel.root_dir').'/cache/*/translations/catalogue.'. $locale .'.*';

                foreach (glob($cachedFiles) as $file) {
                     unlink($file);
                }
            }
        }

        if($this->get('session')->getFlashBag()->has('redirect')) $url = $this->get('session')->getFlashBag()->get('redirect');
        elseif($request->headers->get('referer')) $url = $request->headers->get('referer');
        else $url = $this->get('router')->generate('parabol_admin_core_dashboard');

        return $this->redirect($url);
    }


}
