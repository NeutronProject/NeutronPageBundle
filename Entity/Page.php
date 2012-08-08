<?php
/*
 * This file is part of NeutronAdminBundle
*
* (c) Zender <azazen09@gmail.com>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/
namespace Neutron\PageBundle\Entity;

use Neutron\TreeBundle\Model\TreeNodeInterface;

use Neutron\Bundle\FormBundle\Model\ImageInterface;

use Neutron\PageBundle\Model\PageInterface;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\TranslationEntity(class="Neutron\PageBundle\Entity\Translation\PageTranslation")
 * @ORM\Table(name="neutron_page")
 * @ORM\Entity(repositoryClass="Neutron\PageBundle\Entity\Repository\PageRepository")
 * 
 */
class Page implements PageInterface
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string 
     * @Gedmo\Translatable
     * @ORM\Column(type="string", name="title", length=255, nullable=true, unique=false)
     */
    protected $title;
    
    /**
     * @var string 
     * @Gedmo\Translatable
     * @ORM\Column(type="text", name="content", nullable=true)
     */
    protected $content;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="template", length=50, nullable=true, unique=false)
     */
    protected $template;
    
    /**
     * @ORM\OneToOne(targetEntity="PageImage", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    protected $pageImage;
    
    /**
     * @ORM\OneToOne(targetEntity="Neutron\TreeBundle\Model\TreeNodeInterface")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setTemplate($template)
    {
        $this->template = (string) $template;
        return $this;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function setPageImage(ImageInterface $image)
    {
        $this->pageImage = $image;
        return $this;
    }
    
    public function getPageImage()
    {
        return $this->pageImage;
    }
    
    public function setCategory(TreeNodeInterface $category)
    {
        $this->category = $category;
        return $this;
    }
    
    public function getCategory()
    {
        return $this->category;
    }
}