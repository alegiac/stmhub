<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WidgetUseService
 *
 * @ORM\Table(name="widget_use_service", indexes={@ORM\Index(name="fk_widget_has_service_service1_idx", columns={"service_id"}), @ORM\Index(name="fk_widget_has_service_widget1_idx", columns={"widget_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\WidgetUseServiceRepo")
 */
class WidgetUseService
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
     * @var \Platform\Entity\Widget
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Widget")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="widget_id", referencedColumnName="id")
     * })
     */
    private $widget;

    /**
     * @var \Platform\Entity\Service
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     * })
     */
    private $service;



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
     * @return WidgetUseService
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
     * Set widget
     *
     * @param \Platform\Entity\Widget $widget
     *
     * @return WidgetUseService
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

    /**
     * Set service
     *
     * @param \Platform\Entity\Service $service
     *
     * @return WidgetUseService
     */
    public function setService(\Platform\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Platform\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
