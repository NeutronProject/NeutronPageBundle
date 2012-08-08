<?php
namespace Neutron\PageBundle\Model;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Neutron\Bundle\FormBundle\Model\ImageInterface;

interface PageInterface
{
    public function setTitle($title);
    
    public function getTitle();
    
    public function setPageImage(ImageInterface $image);
    
    public function getPageImage();
    
    public function setCategory(TreeNodeInterface $category);
    
    public function getCategory();
    
    public function setContent($content);
    
    public function getContent();
    
    public function setTemplate($template);
    
    public function getTemplate();
}