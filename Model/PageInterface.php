<?php
namespace Neutron\Plugin\PageBundle\Model;

use Neutron\SeoBundle\Model\SeoInterface;

use Neutron\TreeBundle\Model\TreeNodeInterface;

interface PageInterface
{
    public function getId();
    
    public function setTitle($title);
    
    public function getTitle();
    
    public function setCategory(TreeNodeInterface $category);
    
    public function getCategory();
    
    public function setContent($content);
    
    public function getContent();
    
    public function setTemplate($template);
    
    public function getTemplate();
    
    public function setSeo(SeoInterface $seo);
    
    public function getSeo();
    
    public function setTranslatableLocale($locale);
}