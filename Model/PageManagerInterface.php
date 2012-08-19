<?php
namespace Neutron\Plugin\PageBundle\Model;

use Neutron\LayoutBundle\Model\Plugin\PluginManagerInterface;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Neutron\Bundle\FormBundle\Model\ImageInterface;

interface PageManagerInterface extends  PluginManagerInterface
{
    public function createPage(TreeNodeInterface $category);
    
    public function updatePage(PageInterface $page);
    
    public function deletePage(PageInterface $page);
    
    public function findPageBy(array $criteria);
}