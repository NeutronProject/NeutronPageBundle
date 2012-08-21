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
            ->setLabel('plugin.page.label')
            ->setDescription('plugin.page.description')
            ->setFrontController('NeutronPageBundle:Frontend\Page:index')
            ->setAdministrationRoute('neutron_page.administration')
            ->setUpdateRoute('neutron_page.update')
            ->setDeleteRoute('neutron_page.delete')
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))
            ->addPanel($this->factory->createPanel(
                'panel_sidebar_left', array(
                    'label' => 'panel.sidebar.left.label',
                    'description' => 'panel.sidebar.left.description'
                )
            ))
            ->addPanel($this->factory->createPanel(
                'panel_sidebar_right', array(
                    'label' => 'panel.sidebar.right.label',
                    'description' => 'panel.sidebar.right.description'
                )
            ))
            ->addPanel($this->factory->createPanel(
                'panel_plugin_above', array(
                    'label' => 'panel.plugin.above.label',
                    'description' => 'panel.plugin.above.right.description'
                )
            ))
            ->addPanel($this->factory->createPanel(
                'panel_plugin_below', array(
                    'label' => 'panel.plugin.below.label',
                    'description' => 'panel.plugin.below.right.description'
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