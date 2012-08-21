<?php
namespace Neutron\Plugin\PageBundle\Entity\Repository;

use Doctrine\ORM\Query;

use Doctrine\ORM\EntityRepository; 

class PageRepository extends  EntityRepository
{
    public function findByCategoryIdQueryBuilder()
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('p.category', 'c')
            ->where('c.id = :id')
            ->andWhere('c.enabled = :enabled')
        ;
        
        return $qb;
    }
    
    public function findByCategoryIdQuery($id, $useCache, $locale)
    {
        $query = $this->findByCategoryIdQueryBuilder()->getQuery();
        $query->setParameters(array('id' => $id, 'enabled' => true));
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $locale
        );
        $query->useResultCache($useCache, 7200, $this->generateCacheId($id, $locale));
        return $query;
    }
    
    public function findByCategoryId($id, $useCache, $locale)
    {
        return $this->findByCategoryIdQuery($id, $useCache, $locale)->getSingleResult();
    }
    
    public function generateCacheId($id, $locale)
    {
        return md5($this->getClassName()) . '_' . md5($id . $locale);
    }
}