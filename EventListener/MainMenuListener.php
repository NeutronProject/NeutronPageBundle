<?php
namespace Neutron\PageBundle\EventListener;

use Knp\Menu\FactoryInterface;

use Knp\Menu\ItemInterface;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

class MainMenuListener
{
   
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $factory = $event->getFactory();
        
        $root = $menu->getRoot();
        
        $pageManagement = $factory->createItem('page_management', array(
            'label' => 'menu.page_management',
            'uri' => 'javascript;',
            'attributes' => array(
                'class' => 'dropdown',
            ),
            'childrenAttributes' => array(
                'class' => 'menu',
            ),
            'extras' => array(
                'safe_label' => true,
                'breadcrumbs' => false,
                'translation_domain' => 'NeutronPageBundle'
            ),
        ));
        
        $root->addChild($pageManagement);
        $pageManagement->moveToPosition(2);
        
        
    }
    
}