<?php
namespace Neutron\Plugin\PageBundle\Controller\Backend;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\SeoBundle\Model\SeoAwareInterface;

use Neutron\Plugin\PageBundle\Model\PageInterface;

use Neutron\SeoBundle\Model\SeoInterface;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\MvcBundle\Plugin\PluginInterface;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Neutron\Plugin\PageBundle\PagePlugin;

class PageController extends ContainerAware
{ 
    public function indexAction()
    {
        $grid = $this->container->get('neutron.datagrid')
            ->get($this->container->getParameter('neutron_page.page_datagrid'));
    
        $template = $this->container->get('templating')->render(
            'NeutronPageBundle:Backend\Page:index.html.twig', array(
                'grid' => $grid,
                'translationDomain' => $this->container->getParameter('neutron_page.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {
        $form = $this->container->get('neutron_page.form.page');
        $handler = $this->container->get('neutron_page.form.handler.page');
        $form->setData($this->getData($id));
    
        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronPageBundle:Backend\Page:update.html.twig', array(
                'form' => $form->createView(),
                'translationDomain' => $this->container->getParameter('neutron_page.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($entity);
            $redirectUrl = $this->container->get('router')->generate('neutron_mvc.category.management');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronPageBundle:Backend\Page:delete.html.twig', array(
                'entity' => $entity,
                'translationDomain' => $this->container->getParameter('neutron_page.translation_domain')
            )
        );
    
        return  new Response($template);
    }
    
    protected function doDelete(PageInterface $page)
    {
        $this->container->get('neutron_admin.acl.manager')
            ->deleteObjectPermissions(ObjectIdentity::fromDomainObject($page->getCategory()));
    
        $this->container->get('neutron_page.page_manager')->delete($page, true);
    }
    
    protected function getCategory($id)
    {
        $treeManager = $this->container->get('neutron_tree.manager.factory')
            ->getManagerForClass($this->container->getParameter('neutron_mvc.category.category_class'));
    
        $category = $treeManager->findNodeBy(array('id' => $id));
    
        if (!$category){
            throw new NotFoundHttpException();
        }
    
        return $category;
    }
    
    protected function getEntity(CategoryInterface $category)
    {
        $manager = $this->container->get('neutron_page.page_manager');
        $entity = $manager->findOneBy(array('category' => $category));
    
        if (!$entity){
            throw new NotFoundHttpException();
        }
    
        return $entity;
    }
    
    
    protected function getSeo(SeoAwareInterface $entity)
    {
    
        if(!$entity->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $this->container->get('neutron_seo.manager')->createSeo();
            $entity->setSeo($seo);
        }
    
        return $entity->getSeo();
    }
    
    protected function getData($id)
    {
        $category = $this->getCategory($id);
        $entity = $this->getEntity($category);
        $seo = $this->getSeo($entity);
    
        return array(
            'general' => $category,
            'content' => $entity,
            'seo'     => $seo,
            'acl' => $this->container->get('neutron_admin.acl.manager')
                ->getPermissions(ObjectIdentity::fromDomainObject($category))
        );
    }
}
