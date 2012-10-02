<?php
namespace Neutron\Plugin\PageBundle\Model;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

interface PageInterface extends PluginInstanceInterface
{
    public function setTitle($title);
    
    public function getTitle();
    
    public function setContent($content);
    
    public function getContent();
    
    public function setTemplate($template);
    
    public function getTemplate();
    
    public function setCategory(CategoryInterface $category);
    
    public function getCategory();
    
    public function setSeo(SeoInterface $seo);
    
    public function getSeo();
}