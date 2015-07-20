<?php

namespace Platform\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuHasPage
 *
 * @ORM\Table(name="menu_has_page", indexes={@ORM\Index(name="fk_menu_has_page_page1_idx", columns={"page_id"}), @ORM\Index(name="fk_menu_has_page_menu1_idx", columns={"menu_id"})})
 * @ORM\Entity(repositoryClass="Platform\Entity\Repository\MenuHasPageRepo")
 */
class MenuHasPage
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
     * @var \Platform\Entity\Menuitem
     *
     * @ORM\ManyToOne(targetEntity="Platform\Entity\Menuitem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * })
     */
    private $menu;

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
     * Set menu
     *
     * @param \Platform\Entity\Menuitem $menu
     *
     * @return MenuHasPage
     */
    public function setMenu(\Platform\Entity\Menuitem $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Platform\Entity\Menuitem
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set page
     *
     * @param \Platform\Entity\Page $page
     *
     * @return MenuHasPage
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
