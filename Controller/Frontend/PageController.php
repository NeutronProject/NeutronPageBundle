<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Neutron\Plugin\PageBundle\PagePlugin;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class PageController extends ContainerAware
{
    
    public function indexAction(CategoryInterface $category)
    {   
        $manager = $this->container->get('neutron_page.page_manager');
        $page = $manager->findOneBy(array('category' => $category));
        
        if (null === $page){
            throw new NotFoundHttpException();
        }

        $manager->loadPanels($page->getId(), PagePlugin::IDENTIFIER);
       
        $template = $this->container->get('templating')
            ->render($page->getTemplate(), array(
                'page'   => $page,     
                'plugin' => $manager->getPlugin()    
            ));
    
        return  new Response($template);
    }
  
}
