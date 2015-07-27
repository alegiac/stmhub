<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientconfiguration
 *
 * @ORM\Table(name="clientconfiguration", indexes={@ORM\Index(name="fk_clientconfiguration_client1_idx", columns={"client_id"})})
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
     * @var \Application\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;



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

    /**
     * Set client
     *
     * @param \Application\Entity\Client $client
     *
     * @return Clientconfiguration
     */
    public function setClient(\Application\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Application\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
