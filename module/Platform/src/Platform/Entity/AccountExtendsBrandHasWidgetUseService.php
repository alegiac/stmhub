<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountExtendsBrandHasWidgetUseService
 *
 * @ORM\Table(name="account_extends_brand_has_widget_use_service", indexes={@ORM\Index(name="fk_account_has_brand_has_widget_use_service_brand_has_widge_idx", columns={"brand_has_widget_use_service_id"}), @ORM\Index(name="fk_account_has_brand_has_widget_use_service_account1_idx", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountExtendsBrandHasWidgetUseServiceRepo")
 */
class AccountExtendsBrandHasWidgetUseService
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
     * @ORM\Column(name="params", type="text", length=65535, nullable=true)
     */
    private $params;

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
     * @var \Platform\Entity\BrandHasWidgetUseService
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\BrandHasWidgetUseService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_has_widget_use_service_id", referencedColumnName="id")
     * })
     */
    private $brandHasWidgetUseService;



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
     * Set params
     *
     * @param string $params
     *
     * @return AccountExtendsBrandHasWidgetUseService
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set account
     *
     * @param \Platform\Entity\Account $account
     *
     * @return AccountExtendsBrandHasWidgetUseService
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
     * Set brandHasWidgetUseService
     *
     * @param \Platform\Entity\BrandHasWidgetUseService $brandHasWidgetUseService
     *
     * @return AccountExtendsBrandHasWidgetUseService
     */
    public function setBrandHasWidgetUseService(\Platform\Entity\BrandHasWidgetUseService $brandHasWidgetUseService = null)
    {
        $this->brandHasWidgetUseService = $brandHasWidgetUseService;

        return $this;
    }

    /**
     * Get brandHasWidgetUseService
     *
     * @return \Platform\Entity\BrandHasWidgetUseService
     */
    public function getBrandHasWidgetUseService()
    {
        return $this->brandHasWidgetUseService;
    }
}
