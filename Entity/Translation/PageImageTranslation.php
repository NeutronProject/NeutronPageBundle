<?php
namespace Neutron\Plugin\PageBundle\Entity\Translation;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="neutron_page_image_translations", indexes={
 *      @ORM\Index(name="neutron_page_image_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class PageImageTranslation extends AbstractTranslation
{}