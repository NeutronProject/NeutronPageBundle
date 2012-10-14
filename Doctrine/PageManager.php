<?php
namespace Neutron\Plugin\PageBundle\Doctrine;

use Neutron\Plugin\PageBundle\Model\PageManagerInterface;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

class PageManager extends AbstractManager implements PageManagerInterface
{
    public function getQueryBuilderForPageManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForPageManagementDataGrid();
    }
}