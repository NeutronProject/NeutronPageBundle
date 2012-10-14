<?php
namespace Neutron\Plugin\PageBundle\Model;

interface PageManagerInterface 
{
    public function getQueryBuilderForPageManagementDataGrid();
}