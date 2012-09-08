<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class PageController extends ContainerAware
{
    
    public function indexAction(TreeNodeInterface $category)
    {   
        $page = $this->container->get('neutron_page.page_manager')
            ->findOneBy(array('category' => $category));
        
        if (null === $page){
            throw new NotFoundHttpException();
        }
        
        $layoutManager = $this->container->get('neutron_page.layout_manager');
        $layoutManager->loadPanels($category->getId());
       
        $template = $this->container->get('templating')
            ->render($page->getTemplate(), array(
                'page'   => $page,     
                'plugin' => $layoutManager->getPlugin()      
            ));
    
        return  new Response($template);
    }
  
}
