<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Neutron\MvcBundle\Provider\PluginProvider;

use Neutron\Plugin\PageBundle\PagePlugin;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Response;


class PageController extends ContainerAware
{   
    public function indexAction(CategoryInterface $category)
    {   
        $plugin = $this->container->get('neutron_mvc.plugin_provider')->get(PagePlugin::IDENTIFIER);
        $mvcManager = $this->container->get('neutron_mvc.mvc_manager');
        $pageManager = $this->container->get($plugin->getManagerServiceId());
        $page = $pageManager->findOneBy(array('category' => $category));
        
        if (null === $page){
            throw new NotFoundHttpException();
        }

        $mvcManager->loadPanels($plugin, $page->getId(), PagePlugin::IDENTIFIER);
       
        $template = $this->container->get('templating')
            ->render($page->getTemplate(), array(
                'page'   => $page,     
                'plugin' => $plugin   
            )
        );
    
        return  new Response($template);
    }
  
}
