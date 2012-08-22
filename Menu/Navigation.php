<?php
namespace Neutron\Plugin\PageBundle\Menu;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

use Neutron\AdminBundle\AdminEvents;

use Knp\Menu\Matcher\Voter\UriVoter;

use Symfony\Component\HttpFoundation\Request;

use Knp\Menu\FactoryInterface;

use Symfony\Component\DependencyInjection\ContainerAware;


class Navigation extends ContainerAware
{
    const IDENTIFIER = 'Navigation';

    public function create(FactoryInterface $factory, array $options)
    {
        $this->container->get('neutron_component.menu.voter')
            ->setUri($this->container->get('request')->getRequestUri());
      
        $pages = $this->container->get('neutron_admin.category.manager')->buildNavigation();
        
        $menu = $factory->createFromArray($pages);
        
        $this->container->get('event_dispatcher')
            ->dispatch(AdminEvents::onMenuConfigure, new ConfigureMenuEvent(self::IDENTIFIER, $factory, $menu));
        
        return $menu;
    }

}