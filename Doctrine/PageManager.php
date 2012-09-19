<?php
namespace Neutron\Plugin\PageBundle\Doctrine;

use Neutron\MvcBundle\Doctrine\AbstractPluginManager;

class PageManager extends AbstractPluginManager
{
    public function getQueryBuilderForPageManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForPageManagementDataGrid();
    }
}