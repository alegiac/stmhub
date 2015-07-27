<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account", uniqueConstraints={@ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})}, indexes={@ORM\Index(name="fk_account_client1_idx", columns={"client_id"}), @ORM\Index(name="fk_account_activationstatus1_idx", columns={"activationstatus_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\AccountRepo")
 */
class Account
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
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordsha1", type="string", length=40, nullable=false)
     */
    private $passwordsha1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_access", type="datetime", nullable=true)
     */
    private $lastAccess;

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
     * @var \Application\Entity\Activationstatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Activationstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activationstatus_id", referencedColumnName="id")
     * })
     */
    private $activationstatus;



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
     * Set username
     *
     * @param string $username
     *
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set passwordsha1
     *
     * @param string $passwordsha1
     *
     * @return Account
     */
    public function setPasswordsha1($passwordsha1)
    {
        $this->passwordsha1 = $passwordsha1;

        return $this;
    }

    /**
     * Get passwordsha1
     *
     * @return string
     */
    public function getPasswordsha1()
    {
        return $this->passwordsha1;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Account
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    /**
     * Get insertDate
     *
     * @return \DateTime
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     *
     * @return Account
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;

        return $this;
    }

    /**
     * Get lastAccess
     *
     * @return \DateTime
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * Set client
     *
     * @param \Application\Entity\Client $client
     *
     * @return Account
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

    /**
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return Account
     */
    public function setActivationstatus(\Application\Entity\Activationstatus $activationstatus = null)
    {
        $this->activationstatus = $activationstatus;

        return $this;
    }

    /**
     * Get activationstatus
     *
     * @return \Application\Entity\Activationstatus
     */
    public function getActivationstatus()
    {
        return $this->activationstatus;
    }
}
