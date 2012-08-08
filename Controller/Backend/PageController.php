<?php
namespace Neutron\PageBundle\Controller\Backend;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\PageBundle\Form\Type\PageType;

use Symfony\Component\Form\FormFactory;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class PageController extends ContainerAware
{
    public function indexAction($categoryId, $pageId = false)
    {   
        
        $page = $this->getPage($categoryId, $pageId);
        $form = $this->container->get('neutron_page.page.form');
        $handler = $this->container->get('neutron_page.page.form.handler');

        $form->setData(array('content' => $page));
        
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
    
    private function getPage($categoryId, $pageId)
    {
        $pageManager = $this->container->get('neutron_page.manager.page');
        
        if ($pageId){
            $page = $pageManager->findPageBy(array('id' => $pageId));
        } else {
            $page = $pageManager->createPage($this->getCategory($categoryId));
        }
        
        if (!$page){
            throw new NotFoundHttpException();
        }
        
        return $page;
    }
}
