<?php
namespace Neutron\Plugin\PageBundle;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Neutron\MvcBundle\Plugin\PluginFactoryInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\MvcBundle\MvcEvents;

use Neutron\MvcBundle\Event\ConfigurePluginEvent;

class PagePlugin
{
    const IDENTIFIER = 'neutron.plugin.page';
    
    protected $dispatcher;
    
    protected $factory;
    
    protected $router;
    
    protected $translator;
    
    protected $translationDomain;
    
    public function __construct(EventDispatcher $dispatcher, PluginFactoryInterface $factory, 
            RouterInterface $router, TranslatorInterface $translator, $translationDomain)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        
    }
    
    public function build()
    {
        $plugin = $this->factory->createPlugin(self::IDENTIFIER);
        $plugin
            ->setLabel($this->translator->trans('plugin.page.label', array(), $this->translationDomain))
            ->setDescription($this->translator->trans('plugin.page.description', array(), $this->translationDomain))
            ->setFrontController('neutron_page.controller.frontend.page:indexAction')
            ->setAdministrationRoute('neutron_page.backend.page')
            ->setUpdateRoute('neutron_page.backend.page.update')
            ->setDeleteRoute('neutron_page.backend.page.delete')
            ->setManagerServiceId('neutron_page.page_manager')
            ->setTreeOptions(array(
                'children_strategy' => 'self',
            ))
        ;
        
        $this->dispatcher->dispatch(
            MvcEvents::onPluginConfigure, 
            new ConfigurePluginEvent($this->factory, $plugin)
        );
        
        return $plugin;
    }
    
}