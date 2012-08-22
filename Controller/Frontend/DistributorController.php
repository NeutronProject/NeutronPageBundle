<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Neutron\UserBundle\Model\BackendRoles;

use Neutron\AdminBundle\Entity\MainTree;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Neutron\AdminBundle\Acl\AclManager;

use Neutron\LayoutBundle\Provider\PluginProvider;

use Neutron\AdminBundle\Doctrine\ORM\CategoryManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class DistributorController extends Controller
{
    public function distributeAction($slug)
    {
        $categoryManager = $this->container->get('neutron_admin.category.manager');
        
        $pluginProvider = $this->container->get('neutron_layout.plugin_provider');
        $category = $categoryManager
            ->findCategoryBySlug($slug, true, $this->container->get('request')->getLocale());
        
        if (false === $this->isAllowed($category)){
            throw new AccessDeniedException();
        }
        
        $plugin = $pluginProvider->get($category->getType());
        $controller = $plugin->getFrontController();
        
        $httpKernel = $this->container->get('http_kernel');
        $response = $httpKernel->forward($controller, array(
            'categoryId'  => $category->getId(),
        ));
        
        return $response;
    }
    
    protected function isAllowed(MainTree $category)
    {
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if ($user != 'anon.' && count(array_intersect($user->getRoles(), BackendRoles::getAdministrativeRoles())) > 0) {
    	    return true;
    	}

        return $securityContext->isGranted('VIEW', $category);
    }
}