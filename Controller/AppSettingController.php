<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\AdminCoreBundle\Entity\AppVar;

use Symfony\Component\Yaml\Yaml;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AppSettingController extends Controller
{
    public function getAbsoluteUploadDir($suffix = '')
    {
        return $this->get('service_container')->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web'.$this->getUploadDir($suffix);
    }

    public function getUploadDir($suffix = '')
    {
        return $this->container->getParameter('upload_dir').DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $this->getRequest()->get('class')).$suffix;
    }


    public function showAction(Request $request)
    {

        $appVars = $this->getDoctrine()
                ->getRepository('AppAdminCoreBundle:AppVar')
                ->createQueryBuilder('a')
                ->leftJoin('a.translations', 'at')
                ->orderBy('a.namespace')
                ->getQuery()
                ->execute();

        

        $fileName = $this->get('service_container')->getParameter('kernel.root_dir').'/config/parameters_app.yml';

        if(!file_exists($fileName)) file_put_contents($fileName, '');

        $currentValues = Yaml::parse(file_get_contents($fileName));
        $currentValues = isset($currentValues['parameters']) ? $currentValues['parameters'] : [];
        
        $fileTypes = [];
        $i18nTypes = [];
        $twigableFields = [];
        $collections = [];
        $postAction = [];

        $ref = isset($currentValues['ref']) ? $currentValues['ref'] : uniqid();

        $builder = $this->createFormBuilder(['id' => $ref, 'filesHash' => $ref]);   
        // $builder->add('filesHash', HiddenType::class, ['data' => $ref]);
        $builder->add('ref', HiddenType::class, ['data' => $ref]);

        foreach($appVars as $appVar)
        {
            $fieldName = str_replace('.', '__',$appVar->getPropertyName());

            $data = $request->isMethod(Request::METHOD_POST) ? null : isset($currentValues[$appVar->getPropertyName()]) ? $currentValues[$appVar->getPropertyName()] : ($appVar->isCollection() ? array() : '');

            $options = array(
                'label' => $appVar->getVarLabel(), 
                'data' =>  $appVar->isFile() ? '' : $data,
                'required' => $appVar->getIsRequired(),
                'mapped' => false,
                'attr' => array(
                    'readonly' => $appVar->getIsReadonly(), 
                    'style' => $appVar->getCss(), 
                    'fieldset' => $appVar->getNamespaceLabel(), 
                    'grid' => $appVar->getGrid(),
                    'class' => $appVar->getCssClass(),
                    'help' => $appVar->getHelp(),
                    'data-name' => $fieldName,
                ),
                'constraints' => $appVar->isCollection() ? null : $appVar->getValidators(),
            );

            if($appVar->getTwigAlias()) $twigableFields[$fieldName] = $appVar->getTwigAlias();


            if($appVar->getI18n())
            {

                $i18nTypes[] = $fieldName;
                $options['data'] = AppVar::createFromValueArray($options['data']);
                $options['fieldType'] = $appVar->getFormType('raw');
                
            }
            elseif($appVar->isCollection())
            {
                $collections[] = $fieldName;
                $options['allow_add'] = true;
                // $options['allow_delete'] = true;
                $options['entry_type'] = '\Parabol\AdminCoreBundle\Form\Type\DynamicType';
                $options['error_bubbling'] = false;
                $options['entry_options'] = array('fields' => $appVar->getVarType(), 'fields_constraints' => $appVar->getValidators());
            }
            elseif($appVar->isFile())
            {
                // $options['attr']['data-file'] = $data;

                $options['class'] = AppVar::class; 
                // $options['required'] = false;

                $fileTypes[] = $fieldName;




                // $options['constraints'] = $this->

                //[ 'raw' => $appVar->getFilePath(), 'parsed' => strtr( $appVar->getFilePath(), [ '%kernel.root_dir%' => $this->get('kernel')->getRootDir() ] ) ];

                // if($options['constraints'] === null) $options['constraints'] = [];
                // $options['constraints'] = new \Symfony\Component\Validator\Constraints\File([
                //             // 'mimeTypes' => ['video/mp4'], 
                //             // 'maxSize' => '95m'
                //     ]);
            }

            if($appVar->getFieldOptions())
            {
                foreach($appVar->getFieldOptions() as $name => $option)
                {
                    $options[$name] = $option;
                }
            }

            if($appVar->getPostAction())
            {
                $postAction[$fieldName] = $appVar->getPostAction();
            }

            if($appVar->getFormType() == 'Ivory\CKEditorBundle\Form\Type\CKEditorType')
            {
                $options['config'] = $this->getCKEditroDefaultConfig();
            } 

            $builder->add($fieldName, $appVar->getFormType(), $options);
        }


        // dump($builder);
        // die();
        
        
        // $builder->add('translations_2', TranslationsType::class);

        $form = $builder->getForm();

        // dump($form);
        // die();

        if($request->isMethod('post'))
        {

            
            $form->handleRequest($request);

            if ($form->isValid()) {

                    $values = $request->get('form');
                    $data = $form->getData();
                    
                    $uploadedFiles = $request->get('uploaded_files');

                    if(!empty($uploadedFiles))
                    {

                        $files  = $this->get('doctrine')
                                    ->getRepository('ParabolFilesUploadBundle:File')
                                    ->createQueryBuilder('f')
                                    ->where('f.id IN (:ids)')
                                    ->setParameter('ids', $uploadedFiles)
                                    ->groupBy('f.context')
                                    ->getQuery()
                                    ->getResult()
                                  ;

                        foreach($files as $file)
                        {
                          $values[$file->getContext()] = str_replace('__','--',$file->getPath());   
                        }

                        $oldFiles = $this->get('doctrine')
                                    ->getRepository('ParabolFilesUploadBundle:File')
                                    ->createQueryBuilder('f')
                                    ->where('f.class = :class')
                                    ->andWhere('f.id NOT IN (:ids)')
                                    ->setParameter('class', AppVar::class)
                                    ->setParameter('ids', $uploadedFiles)
                                    ->getQuery()
                                    ->getResult()
                                  ;

                        $em = $this->get('doctrine')->getEntityManager();

                        foreach($oldFiles as $file)
                        {
                            $em->remove($file);
                        }

                        $em->flush();
                    }

                    foreach($collections as $name)
                    {
                        $values[$name] = $form->get($name)->getData();
                    }

                    foreach($i18nTypes as $name)
                    {
                        $values[ $name ] = array_map(function($item){ return $item['value']; },  $values[ $name ]['translations']);
                    }

                    $values['appParams'] = [];
                    foreach($twigableFields as $field => $alias)
                    {
                        $values['appParams'][$alias] = isset($values[$field]) ? $values[$field] : null;
                    }

                    if(isset( $values['_token'] )) unset($values['_token']);

                    $yaml = str_replace('--', '__',str_replace('__', '.', Yaml::dump(array('parameters' => $values), 2)));
                    file_put_contents($fileName, $yaml);

                    foreach($postAction as $name => $class)
                    {

                        $parts = explode('.', $class);    
                        $this->get($parts[0])->{$parts[1]}($name, $values);   
                    }

                    
                    $containerCache = $this->container->getParameter('kernel.root_dir').'/../var/cache/prod/appProdProjectContainer.php';
                    if(file_exists($containerCache)) unlink($containerCache);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $request->getUri());
                    curl_exec($ch);
                    
                    $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans("action.object.edit.success", array(), 'Admingenerator') );

                    return $this->redirect($request->getUri());
                    
            }
            else
            {
                $this->get('session')->getFlashBag()->add('error',  $this->get('translator')->trans("action.object.edit.error", array(), 'Admingenerator') );
            }
        }
        // die();

        return $this->render('ParabolAdminCoreBundle:AppSetting:show.html.twig', array(
                'form' => $form->createView(),
            ));    
    }


    protected function getCKEditroDefaultConfig()
    {
        return array(
            'height' => '300px',
            'allowedContent' => true,
            'contentsCss' => '/assetic/css/compiled/app.min.css',
            'entities' => false,
            'enterMode' => 2, //CKEDITOR.ENTER_BR
            'shiftEnterMode' => 1, //CKEDITOR.ENTER_P
            // 'protectedSource' => [
            //     '/<i[^>]*><\/i>/g',
            //     '/<span[^>]*><\/span>/g',
            // ],
            'toolbar' => array(
                array(
                    'name' => 'document',
                    'items' => array('Source'),
                    ),
                array(
                    'name' => 'clipboard',
                    'groups' => array('clipboard', 'undo' ), 
                    'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' )
                    ),
                array(
                    'name' => 'editing',
                    'groups' => array( 'find', 'selection', 'spellchecker' ),
                    'items' => array( 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' )
                    ),
                array(
                    'name' => 'colors',
                    'items' => array( 'TextColor', 'BGColor' )
                    ),
                array(
                    'name' => 'links',
                    'items' => array( 'Link', 'Unlink', 'Anchor' )
                    ),
                '/',
                array(
                    'name' => 'basicstyles',
                    'groups' => array( 'basicstyles', 'cleanup' ),
                    'items' => array( 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' )
                    ),
                array(
                    'name' => 'paragraph',
                    'groups' => array( 'list', 'indent', 'blocks', 'align', 'bidi' ),
                    'items' => array( 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' )
                    ),
                array(
                    'name' => 'styles',
                    'items' => array( 'Styles', 'Format', 'FontSize' )
                    ),
                
            ),
            // 'extraPlugins' => implode(',', array_keys($this->getCKEditroDefaultPlugins())),
            // 'filebrowserBrowseUrl' => '/app_dev.php/admin/files/browser',
            // 'filebrowserUploadUrl' => '/uploader/upload.php',
        );
    }
  

}
