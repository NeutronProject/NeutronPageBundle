<?php
namespace Neutron\PageBundle\Doctrine\EventSubscriber;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Event\OnFlushEventArgs;

use Doctrine\ORM\Event\PostFlushEventArgs;

use Doctrine\ORM\Events;

use Doctrine\Common\EventSubscriber;

class CategorySubscriber implements EventSubscriber
{
    protected $categoryClass;
    
    protected $pageClass;
    
    protected $pagesForDeletions = array();
    
    public function __construct($categoryClass, $pageClass)
    {
        $this->categoryClass = $categoryClass;
        $this->pageClass = $pageClass;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->pagesForDeletions = array();
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        
        foreach ($uow->getScheduledEntityDeletions() as $entity){
            if (get_class($entity) == $this->categoryClass){
                $meta = $em->getClassMetadata(get_class($entity));
                $identifier = $meta->getSingleIdentifierFieldName();
                $property = $meta->getReflectionProperty($identifier);
                $id = $property->getValue($entity);
                $page = $this->getPage($em, $id);
                
                if ($page){
                    $this->pagesForDeletions[] = $page;
                }
                
            }
        }
    }
    
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        if (count($this->pagesForDeletions) > 0){
            foreach ($this->pagesForDeletions as $page){
                $eventArgs->getEntityManager()->remove($page);
            }
            
            $eventArgs->getEntityManager()->flush();
        }
    }
    
    private function getPage(EntityManager $em, $id)
    {
        
        $repo = $em->getRepository($this->pageClass);
        
        $qb = $repo->createQueryBuilder('p');
        $qb
            ->innerJoin('p.category', 'c')
            ->where('c.id = :categoryId')
        ;
        
        $query = $qb->getQuery();
        $query->setParameter('categoryId', $id);
        return $query->getOneOrNullResult();
    
    }
    
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush);
    }
}