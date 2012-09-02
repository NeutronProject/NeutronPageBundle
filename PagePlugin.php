<?php
namespace Neutron\Plugin\PageBundle;

use Neutron\Plugin\PageBundle\Model\PageManagerInterface;

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
    
    protected $manager;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, 
            RouterInterface $router, TranslatorInterface $translator, PageManagerInterface $manager)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->manager = $manager;
        
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
            ->setManager($this->manager)
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))
            ->addPanel($this->factory->createPanel(
                'page_panel_sidebar_right', array(
                    'label' => $this->translator->trans('panel.sidebar.right.label', array(), 'NeutronPagePlugin'),
                    'description' => $this->translator->trans('panel.sidebar.tight.description', array(), 'NeutronPagePlugin')
                )
            ))
            ->addPanel($this->factory->createPanel(
                'page_panel_sidebar_left', array(
                    'label' => $this->translator->trans('panel.sidebar.left.label', array(), 'NeutronPagePlugin'),
                    'description' => $this->translator->trans('panel.sidebar.left.description', array(), 'NeutronPagePlugin')
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