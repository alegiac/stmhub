<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountHasWidgetoption
 *
 * @ORM\Table(name="account_has_widgetoption", indexes={@ORM\Index(name="fk_account_has_widgetoption_account1_idx", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountHasWidgetoptionRepo")
 */
class AccountHasWidgetoption
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
     * @ORM\Column(name="widgetoption_id", type="bigint", nullable=false)
     */
    private $widgetoptionId;

    /**
     * @var string
     *
     * @ORM\Column(name="option", type="text", length=65535, nullable=false)
     */
    private $option;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set widgetoptionId
     *
     * @param integer $widgetoptionId
     *
     * @return AccountHasWidgetoption
     */
    public function setWidgetoptionId($widgetoptionId)
    {
        $this->widgetoptionId = $widgetoptionId;

        return $this;
    }

    /**
     * Get widgetoptionId
     *
     * @return integer
     */
    public function getWidgetoptionId()
    {
        return $this->widgetoptionId;
    }

    /**
     * Set option
     *
     * @param string $option
     *
     * @return AccountHasWidgetoption
     */
    public function setOption($option)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return string
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set account
     *
     * @param \Platform\Entity\Account $account
     *
     * @return AccountHasWidgetoption
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
}
