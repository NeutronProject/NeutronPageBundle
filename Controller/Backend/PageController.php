<?php
namespace Neutron\PageBundle\Controller\Backend;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\PageBundle\Form\Type\PageType;

use Symfony\Component\Form\FormFactory;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class PageController extends ContainerAware
{
    
    public function indexAction($id)
    {   
        $category = $this->getCategory($id);
        $page = $this->getPage($category);
        $panels = $this->container->get('neutron_page.layout_manager')->getPanels($id);
        
        $form = $this->container->get('neutron_page.page.form');
        $handler = $this->container->get('neutron_page.page.form.handler');

        $form->setData(array(
            'general' => $category, 
            'content' => $page, 
            'panels' => $panels,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))));
        
        if (null !== $handler->process()){         
            return new Response(json_encode($handler->getResult()));
        }
    
        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Page:index.html.twig', array(
                'form' => $form->createView()
            ));
        
        return  new Response($template);
    }
    
    private function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_admin.category.tree_data_class'));
        
        $category = $treeManager->findNodeBy(array('id' => $id));
        
        if (!$category){
            throw new NotFoundHttpException();
        }
        
        return $category;
    }
    
    private function getPage(TreeNodeInterface $category)
    {
        $pageManager = $this->container->get('neutron_page.manager.page');
        
        $page = $pageManager->findPageBy(array('category' => $category));
        
        if (!$page){
            $page = $pageManager->createPage($category);
        }

        return $page;
    }
    
}
