<?php

namespace Parabol\AdminCoreBundle\Menu;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminMenuBuilder extends AdmingeneratorMenuBuilder  implements ContainerAwareInterface
{
    use ContainerAwareTrait;    

    public function __construct(FactoryInterface $factory, RequestStack $requestStack, $dashboardRoute, $container)
    {
        $this->setContainer($container);
        parent::__construct($factory, $requestStack, $dashboardRoute);
    }

    protected function isCurrentUri($uri)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        return ($request->getScheme() . '://' . $request->getHost().$request->getBaseUrl().$request->getPathInfo()) == $uri;
    }

    public function sidebarMenu(array $options)
    {
        $sc = $this->container->get('security.authorization_checker');



        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'sidebar-menu'));

        $this->addLinkRoute($menu, 'Aparthotele', 'ArteryBundle_Aparthotel_list')
        ->setExtra('icon', 'fa fa-building-o');

        //  $this->addLinkRoute($menu, 'Blog', 'Parabol_BlogAdminBundle_BlogPost_list')
        // ->setExtra('icon', 'fa fa-th-large');

        $this->addLinkRoute($menu, 'Wydarzenia', 'Parabol_AdminCoreBundle_Post_list')
        ->setExtra('icon', 'fa fa-calendar');

        $this->addLinkRoute($menu, 'Atrakcje', 'Parabol_AdminCoreBundle_Attraction_list')
        ->setExtra('icon', 'fa fa-star');

        $this->addLinkRoute($menu, 'Strony', 'Parabol_AdminCoreBundle_Page_list')
        ->setExtra('icon', 'fa fa-file');

        $this->addLinkRoute($menu, 'Udogodnienia', 'ArteryBundle_Amenity_list')
        ->setExtra('icon', 'fa fa-wheelchair');

        $this->addLinkRoute($menu, 'Wyposażenie', 'ArteryBundle_Equipment_list')
        ->setExtra('icon', 'fa fa-television');

        $this->addLinkRoute($menu, 'Usługi dodatkowe', 'ArteryBundle_Additional_list')
        ->setExtra('icon', 'fa fa-shopping-bag');

        $this->addLinkRoute($menu, 'Posiłki', 'ArteryBundle_Meal_list')
         ->setExtra('icon', 'fa fa-cutlery');

        $this->addLinkRoute($menu, 'Kod Head/Body', 'Parabol_AdminCoreBundle_Code_list')
         ->setExtra('icon', 'fa fa-code');

  



        // $this->addLinkRoute($menu, 'Znakowania', 'AppBundle_MarkingType_list');

            // ->setExtra('icon', 'glyphicon glyphicon-list');  

        // if(!$this->container->getParameter('parabol_admin_core.dashboard.disabled'))
        // {

        //     if ($dashboardRoute = $this->container->getParameter('admingenerator.dashboard_route')) {
        //         $this
        //             ->addLinkRoute($menu, 'admingenerator.dashboard', $dashboardRoute)
        //             ->setExtra('icon', 'fa fa-dashboard');
        //     }
        // }

        // if(!$this->container->getParameter('parabol_admin_core.post.disabled'))
        // {
        //     $this->addLinkRoute($menu, 'Posts', 'Parabol_AdminCoreBundle_Post_list')
        //     ->setExtra('icon', 'glyphicon glyphicon-list');  
        // }
        
        // $this->addLinkRoute($menu, 'WebPages', 'Parabol_AdminCoreBundle_Page_list')
        // ->setExtra('icon', 'glyphicon glyphicon-file');
        
        // if(!$this->container->getParameter('parabol_admin_core.gallery.disabled'))
        // {
        //     $this->addLinkRoute($menu, 'Galleries', 'Parabol_AdminCoreBundle_Gallery_list')
        //     ->setExtra('icon', 'glyphicon glyphicon-picture');
        // }

        // if(!$this->container->getParameter('parabol_admin_core.text_block.disabled'))
        // {
        //     $this->addLinkRoute($menu, 'Text Blocks', 'Parabol_AdminCoreBundle_TextBlock_list')
        //     ->setExtra('icon', 'glyphicon glyphicon-th-large');
        // }


  

        // if(!$this->container->getParameter('parabol_admin_core.menu.disabled'))
        // {
        //     $this->addLinkRoute($menu, 'Menu', 'Parabol_AdminCoreBundle_MenuItem_list')
        //     ->setExtra('icon', 'glyphicon glyphicon-th-list');
        // }

        // $this->addLinkRoute($menu, 'Seo', 'Parabol_AdminCoreBundle_Seo_list')
        // ->setExtra('icon', 'glyphicon glyphicon-search');
        // // $this->addLinkRoute($menu, 'Team', 'Parabol_TeamBundle_Team_list')
        // // ->setExtra('icon', 'glyphicon glyphicon-flag');  

     
        if($sc->isGranted('ROLE_ADMIN'))
        {
            $this->addLinkRoute($menu, 'Users', 'Parabol_AdminCoreBundle_User_list')
            ->setExtra('icon', 'glyphicon glyphicon-user');  
        }
       

               $this->addLinkRoute($menu, 'Tłumaczenia statyczne', 'Parabol_LocaleAdminBundle_Locale_list')
         ->setExtra('icon', 'fa fa-language');
  


        if(!$this->container->getParameter('parabol_admin_core.app_setting.disabled') && $sc->isGranted('ROLE_ADMIN'))
        {
             $this->addLinkRoute($menu, 'Setting', 'parabol_admin_core_setting')
            ->setExtra('icon', 'glyphicon glyphicon-cog');  
        }


        // $advanced = $this->addDropdown($menu, 'Advanced')->setExtra('icon', 'glyphicon glyphicon-sunglasses');;

        // $this->addLinkRoute($advanced, 'Custom CSS', 'parabol_admin_core_custom_file', ['type' => 'css', 'filename' => 'custom-style.css'])
        //     ->setExtra('icon', 'glyphicon glyphicon-file'); 

        // $this->addLinkRoute($advanced, 'Custom JS', 'parabol_admin_core_custom_file', ['type' => 'js', 'filename' => 'custom-main.js'])
        //     ->setExtra('icon', 'glyphicon glyphicon-align-justify'); 





        // foreach($this->container->getParameter('parabol_admin_core.admin_menu') as $item)
        // {
        //     if(!$item['route']) 
        //     {
        //         ${$item['name']} = $this->addDropdown($menu, $item['label'])->setExtra('icon', $item['icon']);
        //     }
        //     else
        //     {
        //         $this->addLinkRoute($item['parent'] ? ${$item['parent']} : $menu , $item['label'], $item['route'])
        //         ->setExtra('icon', $item['icon']);
        //     }
        // }


        return $menu;
    }

    public function createItem($name, array $options = array()) {

    }
}
