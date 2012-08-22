<?php 
namespace Neutron\Plugin\PageBundle\EventListener;

use Neutron\AdminBundle\Menu\Main;

use Neutron\LayoutBundle\Plugin\PluginInterface;

use Knp\Menu\FactoryInterface;

use Neutron\AdminBundle\Event\ConfigureMenuEvent;

class MainMenuListener
{
   
    protected $plugin;
    
    public function __construct(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        
        if ($event->getIdentifier() !== Main::IDENTIFIER){
            return;
        }
        
        $menu = $event->getMenu();
        $factory = $event->getFactory();
        
        
        $plugins = $menu->getRoot()->getChild('plugins');
        
        $plugins->addChild($this->plugin->getName(), array(
            'label' => $this->plugin->getLabel(),
            'route' => $this->plugin->getAdministrationRoute(),
            'extras' => array(
                'breadcrumbs' => true,
                'translation_domain' => 'NeutronPageBundle'
            ),
        ));
        

    }

    
}