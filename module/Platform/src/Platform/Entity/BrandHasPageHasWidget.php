<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrandHasPageHasWidget
 *
 * @ORM\Table(name="brand_has_page_has_widget", indexes={@ORM\Index(name="fk_brand_has_page_has_widget_page_has_widget1_idx", columns={"page_has_widget_id"}), @ORM\Index(name="fk_brand_has_page_has_widget_brand1_idx", columns={"brand_id"}), @ORM\Index(name="fk_brand_has_page_has_widget_widget1_idx", columns={"widget_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\BrandHasPageHasWidgetRepo")
 */
class BrandHasPageHasWidget
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
     * @var \Platform\Entity\Brand
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @var \Platform\Entity\PageHasWidget
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\PageHasWidget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_has_widget_id", referencedColumnName="id")
     * })
     */
    private $pageHasWidget;

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
     * Set brand
     *
     * @param \Platform\Entity\Brand $brand
     *
     * @return BrandHasPageHasWidget
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
     * Set pageHasWidget
     *
     * @param \Platform\Entity\PageHasWidget $pageHasWidget
     *
     * @return BrandHasPageHasWidget
     */
    public function setPageHasWidget(\Platform\Entity\PageHasWidget $pageHasWidget = null)
    {
        $this->pageHasWidget = $pageHasWidget;

        return $this;
    }

    /**
     * Get pageHasWidget
     *
     * @return \Platform\Entity\PageHasWidget
     */
    public function getPageHasWidget()
    {
        return $this->pageHasWidget;
    }

    /**
     * Set widget
     *
     * @param \Platform\Entity\Widget $widget
     *
     * @return BrandHasPageHasWidget
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
