<?php
namespace Neutron\Plugin\PageBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Neutron\MvcBundle\Form\Type\AbstractPluginInstanceType;

class PageInstanceType extends AbstractPluginInstanceType
{
    public function getName()
    {
        return 'neutron_page_page_instance';
    }
}