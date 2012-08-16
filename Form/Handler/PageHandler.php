<?php
namespace Neutron\Plugin\PageBundle\Form\Handler;

use Neutron\LayoutBundle\Model\LayoutManagerInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\TreeBundle\Model\TreeManagerFactoryInterface;

use Neutron\AdminBundle\Acl\AclManagerInterface;

use Neutron\Plugin\PageBundle\Model\PageManagerInterface;

use Neutron\ComponentBundle\Form\Handler\FormHandlerInterface;

use Neutron\Plugin\PageBundle\Model\PageInterface;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Request;

class PageHandler implements FormHandlerInterface
{
    
    protected $em;
    protected $request;
    protected $router;
    protected $translator;
    protected $form;
    protected $formHelper;
    protected $pageManager;
    protected $aclManager;
    protected $layoutManager;
    protected $treeManager;
    protected $result;


    public function __construct(EntityManager $em, Form $form, FormHelper $formHelper, Request $request, Router $router, 
            TranslatorInterface $translator, PageManagerInterface $pageManager, AclManagerInterface $aclManager, 
            LayoutManagerInterface $layoutManager, TreeManagerFactoryInterface $treeManager, $treeClass)
    {
        $this->em = $em;
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->translator = $translator;
        $this->pageManager = $pageManager;
        $this->aclManager = $aclManager;
        $this->layoutManager = $layoutManager;
        $this->treeManager = $treeManager->getManagerForClass($treeClass);
    }

    public function process()
    {
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bind($this->request);
 
            if ($this->form->isValid()) {
                
                $this->onSucess();
            
                
                $this->request->getSession()
                    ->getFlashBag()->add('neutron.form.success', array(
                        'type' => 'success',
                        'body' => $this->translator->trans('page.flash.updated', array(), 'NeutronPageBundle')
                    ));
                
                $this->result = array(
                    'success' => true,
                    'redirect_uri' => 
                        $this->router->generate('neutron_page.page', array('id' => $this->request->get('id')))
                );
                
                return true;
  
            } else {
                $this->result = array(
                    'success' => false,
                    'errors' => $this->formHelper->getErrorMessages($this->form, 'NeutronPageBundle')
                );
                
                return false;
            }
  
        }
    }
    
    protected function onSucess()
    {
        $pageManager = $this->pageManager;
        $treeManager = $this->treeManager;
        $aclManager = $this->aclManager;
        $layoutManager = $this->layoutManager;
        
        $node = $this->form->get('general')->getData();
        $page = $this->form->get('content')->getData();
        $panels = $this->form->get('panels')->getData();
        $acl = $this->form->get('acl')->getData();
        
        $this->em->transactional(function(EntityManager $em)
                use ($pageManager, $treeManager, $aclManager, $layoutManager, $node, $page, $panels, $acl){
        
            $pageManager->updatePage($page);
            $treeManager->updateNode($node);
            $layoutManager->updatePanels($panels);
            $aclManager
                ->setObjectPermissions(ObjectIdentity::fromDomainObject($node), $acl);
        });
    }
    
    public function getResult()
    {
        return $this->result;
    }

   
}
