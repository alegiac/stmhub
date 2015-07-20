<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountHasBrandHasWidget
 *
 * @ORM\Table(name="account_has_brand_has_widget", indexes={@ORM\Index(name="fk_account_has_brand_has_widget1_brand_has_widget1_idx", columns={"brand_has_widget_id"}), @ORM\Index(name="fk_account_has_brand_has_widget1_account1_idx", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\AccountHasBrandHasWidgetRepo")
 */
class AccountHasBrandHasWidget
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
     * @ORM\Column(name="structure", type="text", length=65535, nullable=true)
     */
    private $structure;

    /**
     * @var string
     *
     * @ORM\Column(name="graphic", type="text", length=65535, nullable=true)
     */
    private $graphic;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="text", length=65535, nullable=true)
     */
    private $context;

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
     * @var \Platform\Entity\BrandHasWidget
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\BrandHasWidget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_has_widget_id", referencedColumnName="id")
     * })
     */
    private $brandHasWidget;



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
     * Set structure
     *
     * @param string $structure
     *
     * @return AccountHasBrandHasWidget
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set graphic
     *
     * @param string $graphic
     *
     * @return AccountHasBrandHasWidget
     */
    public function setGraphic($graphic)
    {
        $this->graphic = $graphic;

        return $this;
    }

    /**
     * Get graphic
     *
     * @return string
     */
    public function getGraphic()
    {
        return $this->graphic;
    }

    /**
     * Set context
     *
     * @param string $context
     *
     * @return AccountHasBrandHasWidget
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set account
     *
     * @param \Platform\Entity\Account $account
     *
     * @return AccountHasBrandHasWidget
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
     * Set brandHasWidget
     *
     * @param \Platform\Entity\BrandHasWidget $brandHasWidget
     *
     * @return AccountHasBrandHasWidget
     */
    public function setBrandHasWidget(\Platform\Entity\BrandHasWidget $brandHasWidget = null)
    {
        $this->brandHasWidget = $brandHasWidget;

        return $this;
    }

    /**
     * Get brandHasWidget
     *
     * @return \Platform\Entity\BrandHasWidget
     */
    public function getBrandHasWidget()
    {
        return $this->brandHasWidget;
    }
}
