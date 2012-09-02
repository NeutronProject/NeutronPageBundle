<?php
namespace Neutron\Plugin\PageBundle\Doctrine\ORM;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Doctrine\ORM\EntityManager;

use Neutron\Plugin\PageBundle\Model\PageInterface;

use Neutron\Plugin\PageBundle\Model\PageManagerInterface;

class PageManager implements PageManagerInterface 
{
        
    protected $em;
    
    protected $class;
    
    protected $repository;
    
    
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository($class);
        $metadata = $this->em->getClassMetadata($class);
        $this->class = $metadata->name;
    }
    

    public function createPage(TreeNodeInterface $category)
    {
        $class = $this->class;
        $entity = new $class();
        $entity->setCategory($category);
        
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }
    
    public function updatePage(PageInterface $page)
    {
        $this->em->persist($page);
        $this->em->flush();
    }
    
    public function deletePage(PageInterface $page)
    {
        $this->em->remove($page);
        $this->em->flush();
    }
    
    public function findPageBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    public function findByCategoryId($id, $useCache = true, $locale = 'en')
    { 
        return $this->repository->findByCategoryId($id, $useCache, $locale);
    }
}