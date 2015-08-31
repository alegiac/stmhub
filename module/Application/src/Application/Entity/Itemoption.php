<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itemoption
 *
 * @ORM\Table(name="itemoption", indexes={@ORM\Index(name="fk_itemoption_item1_idx", columns={"item_id"}), @ORM\Index(name="fk_itemoption_itemoptiontype1_idx", columns={"itemoptiontype_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ItemoptionRepo")
 */
class Itemoption
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct", type="integer", nullable=false)
     */
    private $correct = '0';

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
     * @var \Application\Entity\Itemoptiontype
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Itemoptiontype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="itemoptiontype_id", referencedColumnName="id")
     * })
     */
    private $itemoptiontype;



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
     * @return Itemoption
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
     * Set points
     *
     * @param integer $points
     *
     * @return Itemoption
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set correct
     *
     * @param integer $correct
     *
     * @return Itemoption
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return integer
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set item
     *
     * @param \Application\Entity\Item $item
     *
     * @return Itemoption
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
     * Set itemoptiontype
     *
     * @param \Application\Entity\Itemoptiontype $itemoptiontype
     *
     * @return Itemoption
     */
    public function setItemoptiontype(\Application\Entity\Itemoptiontype $itemoptiontype = null)
    {
        $this->itemoptiontype = $itemoptiontype;

        return $this;
    }

    /**
     * Get itemoptiontype
     *
     * @return \Application\Entity\Itemoptiontype
     */
    public function getItemoptiontype()
    {
        return $this->itemoptiontype;
    }
}
