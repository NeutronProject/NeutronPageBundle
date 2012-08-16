<?php
/*
 * This file is part of NeutronPageBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\PageBundle\Entity;

use Neutron\Bundle\FormBundle\Entity\AbstractImage;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\TranslationEntity(class="Neutron\Plugin\PageBundle\Entity\Translation\PageImageTranslation")
 * @ORM\Table(name="neutron_page_image")
 * @ORM\Entity
 * 
 */
class PageImage extends AbstractImage
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function getUploadDir()
    {
        return '/media/images/page';
    }
}