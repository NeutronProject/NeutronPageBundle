<?php
namespace Neutron\PageBundle\Form\Handler;

use Neutron\ComponentBundle\Form\Handler\FormHandlerInterface;

use Neutron\PageBundle\Model\PageInterface;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Request;

class PageHandler implements FormHandlerInterface
{
    
    protected $request;
    protected $router;
    protected $form;
    protected $formHelper;
    protected $manager;
    protected $result;


    public function __construct(Form $form, FormHelper $formHelper, 
            Request $request, Router $router, $manager)
    {
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->manager = $manager;
    }

    public function process()
    {
  
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bind($this->request);
 
            if ($this->form->isValid()) {
          
                $data = $this->form->getData();
                $this->manager->updatePage($data['content']);
                
                $this->request->getSession()
                    ->getFlashBag()->add('neutron_page.create.success', 'neutron_page.create.success.flash');
                
                $this->result = array(
                    'success' => true,
                    //'redirect_uri' => $this->router->generate('neutron_admin')
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
    


    public function getResult()
    {
        return $this->result;
    }

   
}
