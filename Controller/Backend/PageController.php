<?php
namespace Neutron\PageBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class PageController extends ContainerAware
{
    public function indexAction()
    {
        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Page:index.html.twig', array());
        
        return  new Response($template);
    }
}
