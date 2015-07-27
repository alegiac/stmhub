<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item", indexes={@ORM\Index(name="fk_item_itemtype1_idx", columns={"itemtype_id"}), @ORM\Index(name="fk_item_item1_idx", columns={"item_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ItemRepo")
 */
class Item
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
     * @ORM\Column(name="question", type="text", length=65535, nullable=false)
     */
    private $question;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxtries", type="integer", nullable=false)
     */
    private $maxtries = '2';

    /**
     * @var integer
     *
     * @ORM\Column(name="maxsecs", type="integer", nullable=true)
     */
    private $maxsecs;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="text", length=65535, nullable=false)
     */
    private $context;

    /**
     * @var \Application\Entity\Item
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * })
     */
    private $item;

    /**
     * @var \Application\Entity\Itemtype
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Itemtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="itemtype_id", referencedColumnName="id")
     * })
     */
    private $itemtype;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Image", inversedBy="item")
     * @ORM\JoinTable(name="item_has_image",
     *   joinColumns={
     *     @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *   }
     * )
     */
    private $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set question
     *
     * @param string $question
     *
     * @return Item
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set maxtries
     *
     * @param integer $maxtries
     *
     * @return Item
     */
    public function setMaxtries($maxtries)
    {
        $this->maxtries = $maxtries;

        return $this;
    }

    /**
     * Get maxtries
     *
     * @return integer
     */
    public function getMaxtries()
    {
        return $this->maxtries;
    }

    /**
     * Set maxsecs
     *
     * @param integer $maxsecs
     *
     * @return Item
     */
    public function setMaxsecs($maxsecs)
    {
        $this->maxsecs = $maxsecs;

        return $this;
    }

    /**
     * Get maxsecs
     *
     * @return integer
     */
    public function getMaxsecs()
    {
        return $this->maxsecs;
    }

    /**
     * Set context
     *
     * @param string $context
     *
     * @return Item
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set item
     *
     * @param \Application\Entity\Item $item
     *
     * @return Item
     */
    public function setItem(\Application\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \Application\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set itemtype
     *
     * @param \Application\Entity\Itemtype $itemtype
     *
     * @return Item
     */
    public function setItemtype(\Application\Entity\Itemtype $itemtype = null)
    {
        $this->itemtype = $itemtype;

        return $this;
    }

    /**
     * Get itemtype
     *
     * @return \Application\Entity\Itemtype
     */
    public function getItemtype()
    {
        return $this->itemtype;
    }

    /**
     * Add image
     *
     * @param \Application\Entity\Image $image
     *
     * @return Item
     */
    public function addImage(\Application\Entity\Image $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Application\Entity\Image $image
     */
    public function removeImage(\Application\Entity\Image $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImage()
    {
        return $this->image;
    }
}
