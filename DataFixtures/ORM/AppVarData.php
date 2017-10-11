<?php
namespace Aliso\ApartmentBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Parabol\BaseBundle\DataFixtures\BaseAppVarData;

class AppVarData extends BaseAppVarData
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
            ->addAppVar('app_social_media', 'social_media.instagram', 
                [
                    'en' => ['varLabel' => 'Instagram'], 
                    'pl' => ['varLabel' => 'Instagram']
                ],
                ['grid' => 4, 'twigAlias' => 'instagram']
            )
            ->addAppVar('app_social_media', 'social_media.twitter', 
                [
                    'en' => ['varLabel' => 'Twitter'], 
                    'pl' => ['varLabel' => 'Twitter']
                ],
                ['grid' => 4, 'twigAlias' => 'twitter']
            )
            ->addAppVar('app_social_media', 'social_media.googleplus', 
                [
                    'en' => ['varLabel' => 'Google+'], 
                    'pl' => ['varLabel' => 'Google+']
                ],
                ['grid' => 4, 'twigAlias' => 'googleplus']
            )
            ->addAppVar('app_social_media', 'social_media.youtube', 
                [
                    'en' => ['varLabel' => 'Youtube'], 
                    'pl' => ['varLabel' => 'Youtube']
                ],
                ['grid' => 4, 'twigAlias' => 'youtube']
            )
            ->addAppVar('app_social_media', 'social_media.vimeo', 
                [
                    'en' => ['varLabel' => 'Vimeo'], 
                    'pl' => ['varLabel' => 'Vimeo']
                ],
                ['grid' => 4, 'twigAlias' => 'vimeo']
            )
        ;



        $this->manager->flush();
    }




}