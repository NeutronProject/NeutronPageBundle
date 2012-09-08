<?php
namespace Neutron\Plugin\PageBundle\Model;

use Neutron\LayoutBundle\Model\Plugin\PluginInstanceInterface;

interface PageInterface extends PluginInstanceInterface
{
    public function setTitle($title);
    
    public function getTitle();
    
    public function setContent($content);
    
    public function getContent();
    
    public function setTemplate($template);
    
    public function getTemplate();
}