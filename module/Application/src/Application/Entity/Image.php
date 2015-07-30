<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", indexes={@ORM\Index(name="fk_image_mediatype1_idx", columns={"mediatype_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ImageRepo")
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var \Application\Entity\Mediatype
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Mediatype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mediatype_id", referencedColumnName="id")
     * })
     */
    private $mediatype;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Item", mappedBy="image")
     */
    private $item;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set mediatype
     *
     * @param \Application\Entity\Mediatype $mediatype
     *
     * @return Image
     */
    public function setMediatype(\Application\Entity\Mediatype $mediatype = null)
    {
        $this->mediatype = $mediatype;

        return $this;
    }

    /**
     * Get mediatype
     *
     * @return \Application\Entity\Mediatype
     */
    public function getMediatype()
    {
        return $this->mediatype;
    }

    /**
     * Add item
     *
     * @param \Application\Entity\Item $item
     *
     * @return Image
     */
    public function addItem(\Application\Entity\Item $item)
    {
        $this->item[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Application\Entity\Item $item
     */
    public function removeItem(\Application\Entity\Item $item)
    {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItem()
    {
        return $this->item;
    }
}
