<?php
namespace Neutron\Plugin\PageBundle;

use Neutron\LayoutBundle\Model\Plugin\PluginInstanceManagerInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Neutron\LayoutBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\LayoutBundle\LayoutEvents;

use Neutron\LayoutBundle\Event\ConfigurePluginEvent;

class PagePlugin
{
    const IDENTIFIER = 'neutron.plugin.page';
    
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $manager;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, 
            RouterInterface $router, TranslatorInterface $translator, PluginInstanceManagerInterface $manager)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->manager = $manager;
        
    }
    
    public function build()
    {
        $plugin = $this->factory->createPlugin(self::IDENTIFIER);
        $plugin
            ->setLabel($this->translator->trans('plugin.page.label', array(), 'NeutronPagePlugin'))
            ->setDescription($this->translator->trans('plugin.page.description', array(), 'NeutronPagePlugin'))
            ->setFrontController('neutron_page.controller.front:indexAction')
            ->setAdministrationRoute('neutron_page.backend.administration')
            ->setUpdateRoute('neutron_page.backend.page_instance.update')
            ->setDeleteRoute('neutron_page.backend.page_instance.delete')
            ->setManager($this->manager)
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))

        ;
        
        $this->dispatcher->dispatch(
            LayoutEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}