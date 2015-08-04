<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Db\Sql\Ddl\Column\Integer;

/**
 * Itemoption
 *
 * @ORM\Table(name="itemoption")
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
     * @ORM\Column(name="correct",type="integer",nullable=false)
     */
    private $correct;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Item", mappedBy="itemoption")
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
     * Add item
     *
     * @param \Application\Entity\Item $item
     *
     * @return Itemoption
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
