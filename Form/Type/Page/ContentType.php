<?php 
namespace Neutron\Plugin\PageBundle\Form\Type\Page;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class ContentType extends AbstractType
{

    protected $pageClass;
    
    protected $pageImageClass;
    
    protected $templates;
    
    protected $mediaConfig;
    
    public function __construct($pageClass, $pageImageClass, array $templates, array $mediaConfig)
    {
        $this->pageClass = $pageClass;
        
        $this->pageImageClass = $pageImageClass;
        
        $this->templates = $templates;
        
        $this->mediaConfig = $mediaConfig;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
           ->add('headingText', 'text', array(
               'label' => 'form.headingText',
               'translation_domain' => 'NeutronPageBundle'
           ))
           ->add('title', 'text', array(
               'label' => 'form.title',
               'translation_domain' => 'NeutronPageBundle'
           ))
           ->add('pageImage', 'neutron_image_upload', array(
               'label' => 'form.image',
               'required' => false,
               'data_class' => $this->pageImageClass,
               'translation_domain' => 'NeutronPageBundle',
               'configs' => array(
                   'minWidth' => $this->mediaConfig['page_image']['minWidth'],
                   'minHeight' => $this->mediaConfig['page_image']['minHeight'],
                   'extensions' => $this->mediaConfig['page_image']['extensions'],
                   'maxSize' => $this->mediaConfig['page_image']['maxSize'],
               ),
           ))
           ->add('content', 'neutron_tinymce', array(
               'label' => 'Content',
               'translation_domain' => 'NeutronPageBundle',
               'security' => $this->mediaConfig['html_editor']['security'],
               'configs' => array(
                   'theme' => 'advanced', //simple
                   'skin'  => 'o2k7',
                   'skin_variant' => 'black',
                   'height' => 300,
                   'dialog_type' => 'modal',
                   'readOnly' => false,
               ),
           ))
           ->add('template', 'choice', array(
               'choices' => $this->templates,
               'multiple' => false,
               'expanded' => false,
               'attr' => array('class' => 'uniform'),
               'label' => 'form.template',
               'empty_value' => 'form.empty_value',
               'translation_domain' => 'NeutronPageBundle'
           ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->pageClass,
            'validation_groups' => function(FormInterface $form){
                return 'page';
            },
        ));
    }
    
    public function getName()
    {
        return 'neutron_page_form_type_page_content';
    }
    
}