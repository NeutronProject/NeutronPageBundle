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

class PageController extends ContainerAware
{
    
    public function indexAction()
    {
        $grid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_page.page_grid'));
    
        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Page:index.html.twig', array(
                'grid' => $grid
            ));
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {   

        $form = $this->container->get('neutron_page.page.form');
        $handler = $this->container->get('neutron_page.page.form.handler');

        $form->setData($this->getData($id));
        
        if (null !== $handler->process()){         
            return new Response(json_encode($handler->getResult()));
        }
        
    
        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Page:update.html.twig', array(
                'form' => $form->createView(),
            ));
        
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {   
        $category = $this->getCategory($id);
        $page = $this->getPage($category);

        if (!$page instanceof PageInterface){
            throw new NotFoundHttpException();
        }
        
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($category, $page);
            $redirectUrl = $this->container->get('router')->generate('neutron_page.administration');
            return new RedirectResponse($redirectUrl);
        }

        $template = $this->container->get('templating')
            ->render('NeutronPageBundle:Backend\Page:delete.html.twig', array(
                'record' => $page,
            ));
        
        return  new Response($template);
        
    }
    
    protected function doDelete(TreeNodeInterface $category, PageInterface $page)
    {       
        $pageManager = $this->container->get('neutron_page.manager');
        $aclManager = $this->container->get('neutron_admin.acl.manager');
        
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        $em->transactional(function(EntityManager $em) use ($pageManager, $aclManager, $page){
            $aclManager->deleteObjectPermissions(ObjectIdentity::fromDomainObject($page->getCategory()));
            $pageManager->deletePage($page);
        });
    }
    
    protected function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_admin.category.tree_data_class'));
        
        $category = $treeManager->findNodeBy(array('id' => $id));
        
        if (!$category){
            throw new NotFoundHttpException();
        }
        
        return $category;
    }
    
    protected function getPage(TreeNodeInterface $category)
    {
        $pageManager = $this->container->get('neutron_page.manager');
        
        $page = $pageManager->findPageBy(array('category' => $category));
        
        if (!$page){
            $page = $pageManager->createPage($category);
        }

        return $page;
    }
    
    protected function getSeo(PageInterface $page)
    {
        
        if(!$page->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $manager->createSeo();
            $page->setSeo($seo);
        } 
        
        return $page->getSeo();
    }
    
    protected function getData($id)
    {
        $category = $this->getCategory($id);
        $page = $this->getPage($category);
        $seo = $this->getSeo($page);
        $panels = $this->container->get('neutron_page.layout_manager')->getPanelsForUpdate($id);
        
        return array(
            'general' => $category,
            'content' => $page,
            'seo'     => $seo,
            'panels'  => $panels,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))
        );
    }
    
}
