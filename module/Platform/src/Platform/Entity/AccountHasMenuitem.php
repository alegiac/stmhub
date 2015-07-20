<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountHasMenuitem
 *
 * @ORM\Table(name="account_has_menuitem", indexes={@ORM\Index(name="fk_account_has_menuitem_menuitem1_idx", columns={"menuitem_id"}), @ORM\Index(name="fk_account_has_menuitem_account1_idx", columns={"account_id"}), @ORM\Index(name="fk_account_has_menuitem_accountmenustatus1_idx", columns={"accountmenustatus_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountHasMenuitemRepo")
 */
class AccountHasMenuitem
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
     * @var \Platform\Entity\Account
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

    /**
     * @var \Platform\Entity\Menuitem
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Menuitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menuitem_id", referencedColumnName="id")
     * })
     */
    private $menuitem;

    /**
     * @var \Platform\Entity\Accountmenustatus
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Accountmenustatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="accountmenustatus_id", referencedColumnName="id")
     * })
     */
    private $accountmenustatus;



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
     * Set account
     *
     * @param \Platform\Entity\Account $account
     *
     * @return AccountHasMenuitem
     */
    public function setAccount(\Platform\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Platform\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set menuitem
     *
     * @param \Platform\Entity\Menuitem $menuitem
     *
     * @return AccountHasMenuitem
     */
    public function setMenuitem(\Platform\Entity\Menuitem $menuitem = null)
    {
        $this->menuitem = $menuitem;

        return $this;
    }

    /**
     * Get menuitem
     *
     * @return \Platform\Entity\Menuitem
     */
    public function getMenuitem()
    {
        return $this->menuitem;
    }

    /**
     * Set accountmenustatus
     *
     * @param \Platform\Entity\Accountmenustatus $accountmenustatus
     *
     * @return AccountHasMenuitem
     */
    public function setAccountmenustatus(\Platform\Entity\Accountmenustatus $accountmenustatus = null)
    {
        $this->accountmenustatus = $accountmenustatus;

        return $this;
    }

    /**
     * Get accountmenustatus
     *
     * @return \Platform\Entity\Accountmenustatus
     */
    public function getAccountmenustatus()
    {
        return $this->accountmenustatus;
    }
}
