<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Widget
 *
 * @ORM\Table(name="widget", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})}, indexes={@ORM\Index(name="fk_widget_widgettype1_idx", columns={"widgettype_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\WidgetRepo")
 */
class Widget
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
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="structure", type="text", length=65535, nullable=false)
     */
    private $structure;

    /**
     * @var string
     *
     * @ORM\Column(name="graphic", type="text", length=65535, nullable=false)
     */
    private $graphic;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="text", length=65535, nullable=false)
     */
    private $context;

    /**
     * @var \Platform\Entity\Widgettype
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Widgettype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="widgettype_id", referencedColumnName="id")
     * })
     */
    private $widgettype;



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
     * Set code
     *
     * @param string $code
     *
     * @return Widget
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set structure
     *
     * @param string $structure
     *
     * @return Widget
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
     * @return Widget
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
     * @return Widget
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
     * Set widgettype
     *
     * @param \Platform\Entity\Widgettype $widgettype
     *
     * @return Widget
     */
    public function setWidgettype(\Platform\Entity\Widgettype $widgettype = null)
    {
        $this->widgettype = $widgettype;

        return $this;
    }

    /**
     * Get widgettype
     *
     * @return \Platform\Entity\Widgettype
     */
    public function getWidgettype()
    {
        return $this->widgettype;
    }
}
