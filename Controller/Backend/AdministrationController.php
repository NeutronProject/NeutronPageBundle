<?php
namespace Neutron\Plugin\PageBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Router;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Neutron\Plugin\PageBundle\Model\PageInterface;

use Neutron\SeoBundle\Doctrine\ORM\SeoManager;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\Plugin\PageBundle\Form\Type\PageType;

use Symfony\Component\Form\FormFactory;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class AdministrationController extends ContainerAware
{
    
    public function indexAction()
    {
        $grid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_page.page_grid'));
    
        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Administration:index.html.twig', array(
                'grid' => $grid
            ));
    
        return  new Response($template);
    }
 
}
