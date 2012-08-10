<?php
namespace Neutron\PageBundle\Form\Handler;

use Symfony\Component\Translation\TranslatorInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Neutron\TreeBundle\Model\TreeManagerFactoryInterface;

use Neutron\AdminBundle\Acl\AclManagerInterface;

use Neutron\PageBundle\Model\PageManagerInterface;

use Neutron\ComponentBundle\Form\Handler\FormHandlerInterface;

use Neutron\PageBundle\Model\PageInterface;

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
    protected $treeManager;
    protected $result;


    public function __construct(EntityManager $em, Form $form, FormHelper $formHelper, Request $request, Router $router, 
            TranslatorInterface $translator, PageManagerInterface $pageManager, AclManagerInterface $aclManager, 
            TreeManagerFactoryInterface $treeManager, $treeClass)
    {
        $this->em = $em;
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->translator = $translator;
        $this->pageManager = $pageManager;
        $this->aclManager = $aclManager;
        $this->treeManager = $treeManager->getManagerForClass($treeClass);
    }

    public function process()
    {
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bind($this->request);
 
            if ($this->form->isValid()) {
                
                $this->onSucess();
            
                $this->result = array(
                    'success' => true,
                    'successMsg' => $this->translator->trans('form.success', array(), 'NeutronPageBundle')
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
        
        $node = $this->form->get('general')->getData();
        $page = $this->form->get('content')->getData();
        $acl = $this->form->get('acl')->getData();
        
        $this->em->transactional(function(EntityManager $em)
                use ($pageManager, $treeManager, $aclManager, $node, $page, $acl){
        
            $pageManager->updatePage($page);
            $treeManager->updateNode($node);
            $aclManager
                ->setObjectPermissions(ObjectIdentity::fromDomainObject($node), $acl);
        });
    }
    
    public function getResult()
    {
        return $this->result;
    }

   
}
