<?php
namespace Neutron\Plugin\PageBundle\Entity\Repository;

use Gedmo\Translatable\Entity\Repository\TranslationRepository;

class PageRepository extends TranslationRepository
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
    
    public function getQueryBuilderForSearchProvider()
    {
        $qb = $this->createQueryBuilder('p');        
        
        return $qb;
    }
}