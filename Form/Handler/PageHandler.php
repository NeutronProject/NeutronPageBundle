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
        $content = $this->form->get('content')->getData();
        $category = $content->getCategory();

        $this->container->get('neutron_page.page_manager')->update($content);
        
        $acl = $this->form->get('acl')->getData();
        $this->container->get('neutron_admin.acl.manager')
            ->setObjectPermissions(ObjectIdentity::fromDomainObject($category), $acl);
        
        $this->container->get('object_manager')->flush();
    }
    
    protected function getRedirectUrl()
    {
        return $this->container->get('router')->generate('neutron_page.backend.page');
    }
}