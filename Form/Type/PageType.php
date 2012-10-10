<?php
namespace Neutron\Plugin\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractType
{
    protected $plugin;
    
    protected $aclManager;
    
    public function setPlugin(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function setAclManager($aclManager)
    {
        $this->aclManager = $aclManager;
        return $this;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('general', 'neutron_category');
        $builder->add('content', 'neutron_page_content');
        $builder->add('seo', 'neutron_seo');
    
        if (count($this->plugin->getPanels()) > 0){
            $builder->add('panels', 'neutron_panels', array(
                'plugin' => $this->plugin->getName(),
                'pluginIdentifier' => $this->plugin->getName(),
            ));
        }
    
        if ($this->aclManager->isAclEnabled()){
            $builder->add('acl', 'neutron_admin_form_acl_collection', array(
                'masks' => array(
                    'VIEW' => 'View',
                ),
            ));
        }
    
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }
    
    public function getName()
    {
        return 'neutron_page';
    }
}