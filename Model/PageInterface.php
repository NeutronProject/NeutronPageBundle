<?php
namespace Neutron\Plugin\PageBundle\Model;

use Neutron\MvcBundle\Model\Plugin\PluginInstanceInterface;

interface PageInterface
{
    public function setTitle($title);
    
    public function getTitle();
    
    public function setContent($content);
    
    public function getContent();
    
    public function setTemplate($template);
    
    public function getTemplate();
}