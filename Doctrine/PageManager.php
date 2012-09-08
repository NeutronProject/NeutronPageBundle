<?php
namespace Neutron\Plugin\PageBundle\Doctrine;

use Neutron\LayoutBundle\Doctrine\PluginInstanceManager;

use Neutron\LayoutBundle\Doctrine\AbstractPluginManager;

class PageManager extends PluginInstanceManager
{
    public function getQueryBuilderForPageManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForPageManagementDataGrid();
    }
}