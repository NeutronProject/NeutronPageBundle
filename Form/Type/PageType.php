<?php
namespace Neutron\PageBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('general', 'neutron_admin_form_category_general');
        $builder->add('content', 'neutron_page_form_type_page_content');
        $builder->add('acl', 'neutron_admin_form_acl_collection', array(
            'masks' => array(
                'DELETE'   => 'Delete',
                'EDIT'     => 'Edit',
                'VIEW'     => 'View',
            ),
        ));
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
        return 'neutron_page_form_page';
    }
}