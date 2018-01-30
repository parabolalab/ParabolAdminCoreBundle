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
        $menu->setChildrenAttributes(array('class' => 'sidebar-menu tree', 'data-widget' => 'tree'));


        $items = [];

        foreach($this->container->get('parabol.admin_menu')->getItems() as $name => $item)
        {
            if(!$this->container->hasParameter($name . '.disabled') || !$this->container->getParameter($name . '.disabled', false))
            {
                if($item->getParent())
                {
                    if(!isset($items[$item->getParent()])) $items[$item->getParent()] = ['current' => null, 'children' => []];
                    $items[$item->getParent()]['children'][$name] = $item;
                }
                else
                {
                    $items[$name]['current'] = $item;
                }
            }
        }

        foreach ($items as $item) {

            if($item['current']->getRole() === null || $sc->isGranted($item['current']->getRole()))
            {
                if(isset($item['children']))
                {
                    $current = $this->addDropdown($menu, $item['current']->getLabel());

                    foreach($item['children'] as $child)
                    {
                        if($child->getRole() === null || $sc->isGranted($child->getRole()))
                        {
                            $this
                            ->addLinkRoute($current, $child->getLabel(), $child->getRoute(), $child->getRouteParams())
                            ->setExtra('icon', $child->getIcon() ? $child->getIcon() : 'fa fa-angle-right' )
                            ->setExtra('badgeAction', $child->getBadgeAction() ? $child->getBadgeAction() : null );
                        }
                    }
                }
                else
                {
                    $current = $this->addLinkRoute($menu, $item['current']->getLabel(), $item['current']->getRoute(), $item['current']->getRouteParams());
                }

                $current->setExtra('icon', $item['current']->getIcon())
                        ->setExtra('badgeAction', $item['current']->getBadgeAction() ? $item['current']->getBadgeAction() : null );;
            }
        }


        return $menu;
    }

    public function createItem($name, array $options = array()) {

    }
}
