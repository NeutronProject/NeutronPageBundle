<?php
namespace Neutron\PageBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NeutronPageBundle:Backend\Default:index.html.twig', array());
    }
}
