<?php
namespace Neutron\Plugin\PageBundle\Doctrine;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

class PageManager extends AbstractManager
{
    public function getQueryBuilderForPageManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForPageManagementDataGrid();
    }
}