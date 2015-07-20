<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrandHasWidget
 *
 * @ORM\Table(name="brand_has_widget", indexes={@ORM\Index(name="fk_brand_has_widget_widget1_idx", columns={"widget_id"}), @ORM\Index(name="fk_brand_has_widget_brand1_idx", columns={"brand_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\BrandHasWidgetRepo")
 */
class BrandHasWidget
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
     * @var \Platform\Entity\Brand
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

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
     * Set structure
     *
     * @param string $structure
     *
     * @return BrandHasWidget
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
     * @return BrandHasWidget
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
     * @return BrandHasWidget
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
     * Set brand
     *
     * @param \Platform\Entity\Brand $brand
     *
     * @return BrandHasWidget
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
     * Set widget
     *
     * @param \Platform\Entity\Widget $widget
     *
     * @return BrandHasWidget
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
