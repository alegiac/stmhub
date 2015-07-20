<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account", indexes={@ORM\Index(name="fk_account_accountstatus1_idx", columns={"accountstatus_id"}), @ORM\Index(name="fk_account_brand1_idx", columns={"brand_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountRepo")
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
     * @var \Platform\Entity\Accountstatus
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Accountstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="accountstatus_id", referencedColumnName="id")
     * })
     */
    private $accountstatus;

    /**
     * @var \Platform\Entity\Brand
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;



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
     * Set accountstatus
     *
     * @param \Platform\Entity\Accountstatus $accountstatus
     *
     * @return Account
     */
    public function setAccountstatus(\Platform\Entity\Accountstatus $accountstatus = null)
    {
        $this->accountstatus = $accountstatus;

        return $this;
    }

    /**
     * Get accountstatus
     *
     * @return \Platform\Entity\Accountstatus
     */
    public function getAccountstatus()
    {
        return $this->accountstatus;
    }

    /**
     * Set brand
     *
     * @param \Platform\Entity\Brand $brand
     *
     * @return Account
     */
    public function setBrand(\Platform\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Platform\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }
}
