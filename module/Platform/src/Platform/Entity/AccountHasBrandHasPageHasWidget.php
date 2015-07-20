<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountHasBrandHasPageHasWidget
 *
 * @ORM\Table(name="account_has_brand_has_page_has_widget", indexes={@ORM\Index(name="fk_account_has_brand_has_page_has_widget1_brand_has_page_ha_idx", columns={"brand_has_page_has_widget_id"}), @ORM\Index(name="fk_account_has_brand_has_page_has_widget1_account1_idx", columns={"account_id"}), @ORM\Index(name="fk_account_has_brand_has_page_has_widget_widget1_idx", columns={"widget_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountHasBrandHasPageHasWidgetRepo")
 */
class AccountHasBrandHasPageHasWidget
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
     * @var \Platform\Entity\BrandHasPageHasWidget
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\BrandHasPageHasWidget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_has_page_has_widget_id", referencedColumnName="id")
     * })
     */
    private $brandHasPageHasWidget;

    /**
     * @var \Platform\Entity\Widget
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Widget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="widget_id", referencedColumnName="id")
     * })
     */
    private $widget;



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
     * @return AccountHasBrandHasPageHasWidget
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
     * Set brandHasPageHasWidget
     *
     * @param \Platform\Entity\BrandHasPageHasWidget $brandHasPageHasWidget
     *
     * @return AccountHasBrandHasPageHasWidget
     */
    public function setBrandHasPageHasWidget(\Platform\Entity\BrandHasPageHasWidget $brandHasPageHasWidget = null)
    {
        $this->brandHasPageHasWidget = $brandHasPageHasWidget;

        return $this;
    }

    /**
     * Get brandHasPageHasWidget
     *
     * @return \Platform\Entity\BrandHasPageHasWidget
     */
    public function getBrandHasPageHasWidget()
    {
        return $this->brandHasPageHasWidget;
    }

    /**
     * Set widget
     *
     * @param \Platform\Entity\Widget $widget
     *
     * @return AccountHasBrandHasPageHasWidget
     */
    public function setWidget(\Platform\Entity\Widget $widget = null)
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * Get widget
     *
     * @return \Platform\Entity\Widget
     */
    public function getWidget()
    {
        return $this->widget;
    }
}
