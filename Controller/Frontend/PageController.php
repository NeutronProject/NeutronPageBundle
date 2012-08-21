<?php
namespace Neutron\Plugin\PageBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;


class PageController extends Controller
{
    
    public function indexAction()
    {
        $page = $this->get('neutron_page.manager')->findPageBy(array('id' => 28));
        
        $template = $this->get('templating')
            ->render('NeutronPageBundle:Frontend\Template:right_sidebar.html.twig', array(
                'page' => $page
            ));
    
        return  new Response($template);
    }
    
    
}
