<?php
namespace Neutron\PageBundle;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Routing\RouterInterface;

use Neutron\PluginBundle\Plugin\AbstractPlugin;

class PagePlugin extends AbstractPlugin
{
    
    protected $router;
    
    protected $translator;
    
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
        
        parent::__construct();

    }
    
    protected function configure()
    {
        $this->setOptions(array(
            'label' => $this->translator->trans('plugin.page', array(), 'NeutronPagePlugin'),
            'description' => 'some desc',
            'route' => 'neutron_page.page'
        ));
        
        $this->setTreeOptions(array(
            'children_strategy' => 'self',
        ));
    }
    
    public function getName()
    {
        return 'neutron.plugin.page';
    }
}