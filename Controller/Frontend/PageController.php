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
        
        $template = $this->get('templating')
            ->render($page->getTemplate(), array(
                'page' => $page
            ));
    
        return  new Response($template);
    }
    
    
}
