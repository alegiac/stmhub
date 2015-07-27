<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientconfiguration
 *
 * @ORM\Table(name="clientconfiguration")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ClientconfigurationRepo")
 */
class Clientconfiguration
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
     * @var integer
     *
     * @ORM\Column(name="maxusers", type="integer", nullable=false)
     */
    private $maxusers;



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
     * Set maxusers
     *
     * @param integer $maxusers
     *
     * @return Clientconfiguration
     */
    public function setMaxusers($maxusers)
    {
        $this->maxusers = $maxusers;

        return $this;
    }

    /**
     * Get maxusers
     *
     * @return integer
     */
    public function getMaxusers()
    {
        return $this->maxusers;
    }
}
