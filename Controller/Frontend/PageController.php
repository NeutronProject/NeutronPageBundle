<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\Plugin\PageBundle\PagePlugin;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class PageController extends ContainerAware
{   
    public function indexAction($slug)
    {   
 
        $categoryManager = $this->container->get('neutron_mvc.category.manager');
        
        $page = $categoryManager->findOneByCategorySlug(
            $this->container->getParameter('neutron_page.page_class'), 
            $slug,
            $this->container->get('request')->getLocale()
        );
        
        if (null === $page){
            throw new NotFoundHttpException();
        }
    
        if (false === $this->container->get('neutron_admin.acl.manager')->isGranted($page->getCategory(), 'VIEW')){
            throw new AccessDeniedException();
        }

        $template = $this->container->get('templating')
            ->render($page->getTemplate(), array(
                'page'   => $page,      
            )
        );
    
        return  new Response($template);
    }
  
}
