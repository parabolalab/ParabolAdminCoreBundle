<?php
namespace Aliso\ApartmentBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;


class AppVarData extends \Parabol\AdminCoreBundle\Util\AppVarUtil
{
    /**
     * {@inheritDoc}
     */

    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this
            ->addNamespace('app_organization', ['en' => 'Organization Details', 'pl' => 'Szczegóły Organizacji'])
                ->addAppVar('all', 'organization.name', 
                        ['en' => ['varLabel' => 'Organization Name'], 'pl' => ['varLabel' => 'Nazwa Organizacji']], 
                        ['required' => true, 'twigAlias' => 'organizationName']
                )
        ;

        $this
            ->addNamespace('app_google_analitics', ['en' => 'Google Analitics', 'pl' => 'Google Analitics'])
                ->addAppVar('all', 'google.tracking_id', 
                        [
                            'en' => ['varLabel' => 'Tracking ID'], 
                            'pl' => ['varLabel' => 'Identyfikator śledzenia']
                        ],
                        ['twigAlias' => 'googleTrackingId']
                )
        ;
       

        // $this->createAppVar('app_company', 'all', 'company.address', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Company Address'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Adres Firmy')), true, 6);

        // $this->createAppVar('app_company', 'all', 'company.email', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Email'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Email')), true);
        // $this->createAppVar('app_company', 'all', 'company.phone', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Phone'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Telefon')), true);

             
        
        //  //portal
        $this
            ->addNamespace('app_contact_form', ['en' => 'Contact Form', 'pl' => 'Formularz kontaktowy'])
            ->addAppVar('app', 'contact_form.email', 
                [
                    'en' => ['varLabel' => 'Email Address'], 
                    'pl' => ['varLabel' => 'Adres Email']
                ],
                ['required' => true, 'grid' => 12, 'twigAlias' => 'contactFormEmail', 'varType' => 'email']
            )
            ->addAppVar('app', 'contact_form.success_message', 
                [
                    'en' => ['varLabel' => 'Success Message'], 
                    'pl' => ['varLabel' => 'Komunikat po wysłaniu']
                ], 
                ['required' => true, 'grid' => 12, 'twigAlias' => 'contactFormSuccessMessage', 'i18n' => true, 'varType' => 'text']
            )
            ->addAppVar('app', 'contact_form.error_message', 
                [
                    'en' => ['varLabel' => 'Error Message'], 
                    'pl' => ['varLabel' => 'Komunikat błędu']
                ], 
                ['required' => true, 'grid' => 12, 'twigAlias' => 'contactFormSuccessMessage_pl', 'i18n' => true, 'varType' => 'text']
            )
        ;
        // $this->createAppVar('all', 'contact_form.title', array('en' => array('namespaceLabel' => 'Contact Form', 'varLabel' => 'Title'), 'pl' => array('namespaceLabel' => 'Formularz kontaktowy', 'varLabel' => 'Tytuł')), false);
        

        $this
            ->addNamespace('app_cookies', ['en' => 'Cookies', 'pl' => 'Ciasteczka'])
            ->addAppVar('app', 'cookies.description', [
                    'en' => ['namespaceLabel' => 'Cookies', 'varLabel' => 'Communicate'],
                    'pl' => ['namespaceLabel' => 'Ciasteczka', 'varLabel' => 'Komunikat']
                ], 
                ['grid' => 12, 'twigAlias' => 'cookiesDescription', 'i18n' => true, 'varType' => 'text']
            );

        $this
            ->addNamespace('app_social_media', ['en' => 'Social Media', 'pl' => 'Media społecznościowe'])
            ->addAppVar('app', 'social_media.facebook', 
                [
                    'en' => ['varLabel' => 'Facebook'], 
                    'pl' => ['varLabel' => 'Facebook']
                ],
                ['grid' => 4, 'twigAlias' => 'facebook']
            )
            ->addAppVar('app_social_media', 'all', 'social_media.instagram', 
                [
                    'en' => ['varLabel' => 'Instagram'], 
                    'pl' => ['varLabel' => 'Instagram']
                ],
                ['grid' => 4, 'twigAlias' => 'instagram']
            )
            ->addAppVar('app_social_media', 'all', 'social_media.twitter', 
                [
                    'en' => ['varLabel' => 'Twitter'], 
                    'pl' => ['varLabel' => 'Twitter']
                ],
                ['grid' => 4, 'twigAlias' => 'twitter']
            )
            ->addAppVar('app_social_media', 'all', 'social_media.googleplus', 
                [
                    'en' => ['varLabel' => 'Google+'], 
                    'pl' => ['varLabel' => 'Google+']
                ],
                ['grid' => 4, 'twigAlias' => 'googleplus']
            )
            ->addAppVar('app_social_media', 'all', 'social_media.youtube', 
                [
                    'en' => ['varLabel' => 'Youtube'], 
                    'pl' => ['varLabel' => 'Youtube']
                ],
                ['grid' => 4, 'twigAlias' => 'youtube']
            )
            ->addAppVar('app_social_media', 'all', 'social_media.vimeo', 
                [
                    'en' => ['varLabel' => 'Vimeo'], 
                    'pl' => ['varLabel' => 'Vimeo']
                ],
                ['grid' => 4, 'twigAlias' => 'vimeo']
            )
        ;

        //  $this->addAppVar('app_blog', 'all', 'blog.url', 
        //     [
        //         'en' => ['namespaceLabel' => 'Blog', 'varLabel' => 'URL'], 
        //         'pl' => ['namespaceLabel' => 'Media Blog', 'varLabel' => 'URL']
        //     ],
        //     ['twigAlias' => 'blogUrl']
        // );

        

        // //portal
        // $this->createAppVar('app_company', 'all', 'company.name', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Company Name'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Nazwa Firmy')), true, 6);

        // $this->createAppVar('app_company', 'all', 'company.address', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Company Address'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Adres Firmy')), true, 6);

        // $this->createAppVar('app_company', 'all', 'company.email', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Email'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Email')), true);
        // $this->createAppVar('app_company', 'all', 'company.phone', array('en' => array('namespaceLabel' => 'Company', 'varLabel' => 'Phone'), 'pl' => array('namespaceLabel' => 'Company', 'varLabel' => 'Telefon')), true);

        // //google
        // $this->createAppVar('app_google_analitics', 'app', 'google.tracking_id', array('en' => array('namespaceLabel' => 'Google Analitics', 'varLabel' => 'Tracking ID'), 'pl' => array('namespaceLabel' => 'Google Analitics', 'varLabel' => 'Identyfikator śledzenia')));        
        
        //  //portal
        // $this->createAppVar('app_contact_form', 'all', 'contact_form.email', array('en' => array('namespaceLabel' => 'Contact Form', 'varLabel' => 'Email Address'), 'pl' => array('namespaceLabel' => 'Formularz kontaktowy', 'varLabel' => 'Adres Email')), true);
        // $this->createAppVar('app_contact_form', 'all', 'contact_form.title', array('en' => array('namespaceLabel' => 'Contact Form', 'varLabel' => 'Title'), 'pl' => array('namespaceLabel' => 'Formularz kontaktowy', 'varLabel' => 'Tytuł')), false);
        // $this->createAppVar('app_contact_form', 'all', 'contact_form.success_message', array('en' => array('namespaceLabel' => 'Contact Form', 'varLabel' => 'Success Message'), 'pl' => array('namespaceLabel' => 'Formularz kontaktowy', 'varLabel' => 'Komunikat po wysłaniu')), true, 6, true, false, 'text');
        // $this->createAppVar('app_contact_form', 'all', 'contact_form.error_message', array('en' => array('namespaceLabel' => 'Contact Form', 'varLabel' => 'Error Message'), 'pl' => array('namespaceLabel' => 'Formularz kontaktowy', 'varLabel' => 'Komunikat błędu')), true, 6, true, false, 'text');

        // $this->createAppVar('app_cookies', 'all', 'cookies.description', array('en' => array('namespaceLabel' => 'Cookies', 'varLabel' => 'Communicate'), 'pl' => array('namespaceLabel' => 'Ciasteczka', 'varLabel' => 'Komunikat')), false, 12, true, false, 'text');

        // $this->createAppVar('app_social_media', 'all', 'social_media.fb', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'Facebook'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'Facebook')), false);
        // $this->createAppVar('app_social_media', 'all', 'social_media.twitter', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'Twitter'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'Twitter')), false);
        // $this->createAppVar('app_social_media', 'all', 'social_media.instagram', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'Instagram'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'Instagram')), false);
        // $this->createAppVar('app_social_media', 'all', 'social_media.googleplus', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'Google+'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'Google+')), false);
        // $this->createAppVar('app_social_media', 'all', 'social_media.youtube', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'YouTube'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'YouTube')), false);
        // $this->createAppVar('app_social_media', 'all', 'social_media.vimeo', array('en' => array('namespaceLabel' => 'Social Media', 'varLabel' => 'Vimeo'), 'pl' => array('namespaceLabel' => 'Media społecznościowe', 'varLabel' => 'Vimeo')), false);


        $this->manager->flush();
    }




}