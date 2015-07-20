<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountHasPage
 *
 * @ORM\Table(name="account_has_page", indexes={@ORM\Index(name="fk_account_has_page_page1_idx", columns={"page_id"}), @ORM\Index(name="fk_account_has_page_account1_idx", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountHasPageRepo")
 */
class AccountHasPage
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
     * @var \Platform\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;



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
     * @return AccountHasPage
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
     * Set page
     *
     * @param \Platform\Entity\Page $page
     *
     * @return AccountHasPage
     */
    public function setPage(\Platform\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Platform\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
