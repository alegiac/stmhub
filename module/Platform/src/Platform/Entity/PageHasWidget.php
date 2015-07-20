<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageHasWidget
 *
 * @ORM\Table(name="page_has_widget", indexes={@ORM\Index(name="fk_page_has_widget_widget1_idx", columns={"widget_id"}), @ORM\Index(name="fk_page_has_widget_page_idx", columns={"page_id"}), @ORM\Index(name="fk_page_has_widget_widgetsize1_idx", columns={"widgetsize_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\PageHasWidgetRepo")
 */
class PageHasWidget
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
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var \Platform\Entity\Widgetsize
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Widgetsize")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="widgetsize_id", referencedColumnName="id")
     * })
     */
    private $widgetsize;

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
     * Set position
     *
     * @param integer $position
     *
     * @return PageHasWidget
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set widgetsize
     *
     * @param \Platform\Entity\Widgetsize $widgetsize
     *
     * @return PageHasWidget
     */
    public function setWidgetsize(\Platform\Entity\Widgetsize $widgetsize = null)
    {
        $this->widgetsize = $widgetsize;

        return $this;
    }

    /**
     * Get widgetsize
     *
     * @return \Platform\Entity\Widgetsize
     */
    public function getWidgetsize()
    {
        return $this->widgetsize;
    }

    /**
     * Set page
     *
     * @param \Platform\Entity\Page $page
     *
     * @return PageHasWidget
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

    /**
     * Set widget
     *
     * @param \Platform\Entity\Widget $widget
     *
     * @return PageHasWidget
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
