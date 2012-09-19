<?php
namespace Neutron\Plugin\PageBundle\Entity\Repository;

use Neutron\MvcBundle\Entity\Repository\PluginInstanceRepository;

class PageRepository extends PluginInstanceRepository 
{
    public function getQueryBuilderForPageManagementDataGrid()
    {
   
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select('p.id, c.id as category_id, p.title, c.slug, c.title as category, c.enabled, c.displayed')
            ->innerJoin('p.category', 'c')
            ->groupBy('p.id')
        ;
        
        return $qb;
    }
}