<?php
namespace Neutron\PageBundle\Doctrine\ORM;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Doctrine\ORM\EntityManager;

use Neutron\PageBundle\Model\PageInterface;

use Neutron\PageBundle\Model\PageManagerInterface;

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
        $image = $entity->getPageImage();
        
        if ($image){
            $this->em->persist($image);
        }
        
        return $entity;
    }
    
    public function updatePage(PageInterface $page)
    {
        $image = $page->getPageImage();
        
        if ($image){
            $this->em->persist($image);
        }
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
}