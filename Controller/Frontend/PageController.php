<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class PageController extends Controller
{
    
    public function indexAction($categoryId)
    {   
        $page = $this->get('neutron_page.manager')
            ->findByCategoryId($categoryId, true, $this->get('request')->getLocale());
        
        $layoutManager = $this->container->get('neutron_page.layout_manager');
        $layoutManager->loadPanels($categoryId);
        
        $template = $this->get('templating')
            ->render($page->getTemplate(), array(
                'page'   => $page,     
                'plugin' => $layoutManager->getPlugin()      
            ));
    
        return  new Response($template);
    }
  
}
