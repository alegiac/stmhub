<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menuitem
 *
 * @ORM\Table(name="menuitem", indexes={@ORM\Index(name="fk_menu_menustatus1_idx", columns={"menustatus_id"}), @ORM\Index(name="fk_menu_page1_idx", columns={"page_id"}), @ORM\Index(name="fk_menuitem_menuitem1_idx", columns={"menuitem_id"}), @ORM\Index(name="fk_menuitem_menutype1_idx", columns={"menutype_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\MenuitemRepo")
 */
class Menuitem
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="iconurl", type="text", length=65535, nullable=true)
     */
    private $iconurl;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;

    /**
     * @var \Platform\Entity\Menutype
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Menutype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menutype_id", referencedColumnName="id")
     * })
     */
    private $menutype;

    /**
     * @var \Platform\Entity\Menuitem
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Menuitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menuitem_id", referencedColumnName="id")
     * })
     */
    private $menuitem;

    /**
     * @var \Platform\Entity\Menustatus
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Menustatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menustatus_id", referencedColumnName="id")
     * })
     */
    private $menustatus;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Menuitem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set iconurl
     *
     * @param string $iconurl
     *
     * @return Menuitem
     */
    public function setIconurl($iconurl)
    {
        $this->iconurl = $iconurl;

        return $this;
    }

    /**
     * Get iconurl
     *
     * @return string
     */
    public function getIconurl()
    {
        return $this->iconurl;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Menuitem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set menutype
     *
     * @param \Platform\Entity\Menutype $menutype
     *
     * @return Menuitem
     */
    public function setMenutype(\Platform\Entity\Menutype $menutype = null)
    {
        $this->menutype = $menutype;

        return $this;
    }

    /**
     * Get menutype
     *
     * @return \Platform\Entity\Menutype
     */
    public function getMenutype()
    {
        return $this->menutype;
    }

    /**
     * Set menuitem
     *
     * @param \Platform\Entity\Menuitem $menuitem
     *
     * @return Menuitem
     */
    public function setMenuitem(\Platform\Entity\Menuitem $menuitem = null)
    {
        $this->menuitem = $menuitem;

        return $this;
    }

    /**
     * Get menuitem
     *
     * @return \Platform\Entity\Menuitem
     */
    public function getMenuitem()
    {
        return $this->menuitem;
    }

    /**
     * Set menustatus
     *
     * @param \Platform\Entity\Menustatus $menustatus
     *
     * @return Menuitem
     */
    public function setMenustatus(\Platform\Entity\Menustatus $menustatus = null)
    {
        $this->menustatus = $menustatus;

        return $this;
    }

    /**
     * Get menustatus
     *
     * @return \Platform\Entity\Menustatus
     */
    public function getMenustatus()
    {
        return $this->menustatus;
    }

    /**
     * Set page
     *
     * @param \Platform\Entity\Page $page
     *
     * @return Menuitem
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
}
