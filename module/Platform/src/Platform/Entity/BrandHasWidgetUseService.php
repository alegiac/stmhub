<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrandHasWidgetUseService
 *
 * @ORM\Table(name="brand_has_widget_use_service", indexes={@ORM\Index(name="fk_brand_has_widget_use_service1_widget_use_service1_idx", columns={"widget_use_service_id"}), @ORM\Index(name="fk_brand_has_widget_use_service1_brand1_idx", columns={"brand_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\BrandHasWidgetUseServiceRepo")
 */
class BrandHasWidgetUseService
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
     * @var \Platform\Entity\Brand
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @var \Platform\Entity\WidgetUseService
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\WidgetUseService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="widget_use_service_id", referencedColumnName="id")
     * })
     */
    private $widgetUseService;



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
     * @return BrandHasWidgetUseService
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
     * Set brand
     *
     * @param \Platform\Entity\Brand $brand
     *
     * @return BrandHasWidgetUseService
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

    /**
     * Set widgetUseService
     *
     * @param \Platform\Entity\WidgetUseService $widgetUseService
     *
     * @return BrandHasWidgetUseService
     */
    public function setWidgetUseService(\Platform\Entity\WidgetUseService $widgetUseService = null)
    {
        $this->widgetUseService = $widgetUseService;

        return $this;
    }

    /**
     * Get widgetUseService
     *
     * @return \Platform\Entity\WidgetUseService
     */
    public function getWidgetUseService()
    {
        return $this->widgetUseService;
    }
}
