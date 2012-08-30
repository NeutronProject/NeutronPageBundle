<?php
namespace Neutron\Plugin\PageBundle;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Neutron\LayoutBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\LayoutBundle\LayoutEvents;

use Neutron\LayoutBundle\Event\ConfigurePluginEvent;

class PagePlugin
{
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        
    }
    
    public function create()
    {
        $plugin = $this->factory->createPlugin('neutron.plugin.page');
        $plugin
            ->setLabel($this->translator->trans('plugin.page.label', array(), 'NeutronPagePlugin'))
            ->setDescription($this->translator->trans('plugin.page.description', array(), 'NeutronPagePlugin'))
            ->setFrontController('NeutronPageBundle:Frontend\Page:index')
            ->setAdministrationRoute('neutron_page.administration')
            ->setUpdateRoute('neutron_page.update')
            ->setDeleteRoute('neutron_page.delete')
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))
            ->addPanel($this->factory->createPanel(
                'panel_sidebar_right', array(
                    'label' => $this->translator->trans('panel.sidebar.left.label', array(), 'NeutronPagePlugin'),
                    'description' => $this->translator->trans('panel.sidebar.left.description', array(), 'NeutronPagePlugin')
                )
            ))
            ->addPanel($this->factory->createPanel(
                'page_panel_static', array(
                    'label' => $this->translator->trans('page.panel.static.label', array(), 'NeutronPagePlugin'),
                    'description' => $this->translator->trans('page.panel.static.description', array(), 'NeutronPagePlugin')
                )
            ))
        ;
        
        $this->dispatcher->dispatch(
            LayoutEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}