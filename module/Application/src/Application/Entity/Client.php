<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client", indexes={@ORM\Index(name="fk_client_activationstatus1_idx", columns={"activationstatus_id"}), @ORM\Index(name="fk_client_clientconfiguration1_idx", columns={"clientconfiguration_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ClientRepo")
 */
class Client
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
     * @ORM\Column(name="businessname", type="string", length=255, nullable=false)
     */
    private $businessname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=45, nullable=false)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=255, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=45, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="vatnumber", type="string", length=45, nullable=true)
     */
    private $vatnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscode", type="string", length=45, nullable=true)
     */
    private $fiscode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

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
     * @var \Application\Entity\Clientconfiguration
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Clientconfiguration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clientconfiguration_id", referencedColumnName="id")
     * })
     */
    private $clientconfiguration;



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
     * Set businessname
     *
     * @param string $businessname
     *
     * @return Client
     */
    public function setBusinessname($businessname)
    {
        $this->businessname = $businessname;

        return $this;
    }

    /**
     * Get businessname
     *
     * @return string
     */
    public function getBusinessname()
    {
        return $this->businessname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Client
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return Client
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Client
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Client
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set vatnumber
     *
     * @param string $vatnumber
     *
     * @return Client
     */
    public function setVatnumber($vatnumber)
    {
        $this->vatnumber = $vatnumber;

        return $this;
    }

    /**
     * Get vatnumber
     *
     * @return string
     */
    public function getVatnumber()
    {
        return $this->vatnumber;
    }

    /**
     * Set fiscode
     *
     * @param string $fiscode
     *
     * @return Client
     */
    public function setFiscode($fiscode)
    {
        $this->fiscode = $fiscode;

        return $this;
    }

    /**
     * Get fiscode
     *
     * @return string
     */
    public function getFiscode()
    {
        return $this->fiscode;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Client
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
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return Client
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

    /**
     * Set clientconfiguration
     *
     * @param \Application\Entity\Clientconfiguration $clientconfiguration
     *
     * @return Client
     */
    public function setClientconfiguration(\Application\Entity\Clientconfiguration $clientconfiguration = null)
    {
        $this->clientconfiguration = $clientconfiguration;

        return $this;
    }

    /**
     * Get clientconfiguration
     *
     * @return \Application\Entity\Clientconfiguration
     */
    public function getClientconfiguration()
    {
        return $this->clientconfiguration;
    }
}
