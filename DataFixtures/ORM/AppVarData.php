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