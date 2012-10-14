<?php
namespace Neutron\Plugin\PageBundle\Form\Handler;

use Neutron\AdminBundle\Acl\AclManager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\Plugin\PageBundle\PagePlugin;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\ComponentBundle\Form\Handler\AbstractFormHandler;

class PageHandler extends AbstractFormHandler
{    
    protected function onSuccess()
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')->get(PagePlugin::IDENTIFIER);
        $content = $this->form->get('content')->getData();
        $category = $content->getCategory();

        $this->container->get($plugin->getManagerServiceId())->update($content);
        
        if (count($plugin->getPanels()) > 0){
            $panels = $this->form->get('panels')->getData();
            $this->container->get('neutron_mvc.mvc_manager')->updatePanels($content->getId(), $panels);
        }
        
        $acl = $this->form->get('acl')->getData();
        $this->container->get('neutron_admin.acl.manager')
            ->setObjectPermissions(ObjectIdentity::fromDomainObject($category), $acl);
        
        $this->container->get('object_manager')->flush();
    }
    
    protected function getRedirectUrl()
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')
            ->get(PagePlugin::IDENTIFIER);
        
        return $this->container->get('router')->generate($plugin->getAdministrationRoute());
    }
}