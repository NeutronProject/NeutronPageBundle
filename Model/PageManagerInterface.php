<?php
namespace Neutron\PageBundle\Model;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Neutron\Bundle\FormBundle\Model\ImageInterface;

interface PageManagerInterface
{
    public function createPage(TreeNodeInterface $category);
    
    public function updatePage(PageInterface $page);
    
    public function deletePage(PageInterface $page);
    
    public function findPageBy(array $criteria);
}