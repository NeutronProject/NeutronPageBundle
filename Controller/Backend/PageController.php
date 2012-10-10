<?php
namespace Neutron\Plugin\PageBundle\Controller\Backend;

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
            ->get($this->container->getParameter('neutron_page.page_grid'));
    
        $template = $this->container->get('templating')->render(
            'NeutronPageBundle:Backend\Page:index.html.twig', array(
                'grid' => $grid
            )
        );
    
        return  new Response($template);
    }
    
    public function updateAction($id)
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')->get(PagePlugin::IDENTIFIER);
        $form = $this->container->get('neutron_page.form.page');
        $handler = $this->container->get('neutron_page.form.handler.page');
        $form->setData($this->getData($id));
    
        if (null !== $handler->process()){
            return new Response(json_encode($handler->getResult()));
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronPageBundle:Backend\Page:update.html.twig', array(
                'form' => $form->createView(),
                'plugin' => $plugin,
                'translationDomain' => $this->container->get('neutron_page.translationDomain')
            )
        );
    
        return  new Response($template);
    }
    
    public function deleteAction($id)
    {
        $plugin = $this->container->get('neutron_mvc.plugin_provider')->get(PagePlugin::IDENTIFIER);
        $category = $this->getCategory($id);
        $page = $this->getPage($category);
    
        if ($this->container->get('request')->getMethod() == 'POST'){
            $this->doDelete($category, $page);
            $redirectUrl = $this->container->get('router')->generate('neutron_mvc.category.management');
            return new RedirectResponse($redirectUrl);
        }
    
        $template = $this->container->get('templating')->render(
            'NeutronMvcBundle:Backend\PluginInstance:delete.html.twig', array(
                'entity' => $page,
                'plugin' => $plugin,
                'translationDomain' => $this->container->get('neutron_page.translationDomain')
            )
        );
    
        return  new Response($template);
    }
    
    protected function doDelete(PluginInterface $plugin, PageInterface $page)
    {
        $this->container->get('neutron_admin.acl.manager')
            ->deleteObjectPermissions(ObjectIdentity::fromDomainObject($page->getCategory()));
    
        $plugin->getManager()->delete($page, true);
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
    
    protected function getPage(CategoryInterface $category)
    {
        $pageManager = $this->container->get('neutron_page.page_manager');
        $page = $pageManager->findOneBy(array('category' => $category));
    
        if (!$page){
            throw new NotFoundHttpException();
        }
    
        return $page;
    }
    
    
    protected function getSeo(SeoAwareInterface $page)
    {
    
        if(!$page->getSeo() instanceof SeoInterface){
            $manager = $this->container->get('neutron_seo.manager');
            $seo = $this->container->get('neutron_seo.manager')->createSeo();
            $page->setSeo($seo);
        }
    
        return $page->getSeo();
    }
    
    protected function getData($id)
    {
        $mvcManager = $this->container->get('neutron_mvc.mvc_manager');
        $plugin = $this->container->get('neutron_mvc.plugin_provider')->get(PagePlugin::IDENTIFIER);
        $category = $this->getCategory($id);
        $page = $this->getPage($category);
        $seo = $this->getSeo($page);
        $panels = $mvcManager->getPanelsForUpdate($plugin, $page->getId(), $this->plugin->getName());
    
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
