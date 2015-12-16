<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prize
 *
 * @ORM\Table(name="prize", indexes={@ORM\Index(name="fk_prize_client_has_course1_idx", columns={"client_has_course_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\PrizeRepo")
 */
class Prize
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var \Application\Entity\ClientHasCourse
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ClientHasCourse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_has_course_id", referencedColumnName="id")
     * })
     */
    private $clientHasCourse;



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
     * @return Prize
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
     * Set description
     *
     * @param string $description
     *
     * @return Prize
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Prize
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
     * Set url
     *
     * @param string $url
     *
     * @return Prize
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
     * Set clientHasCourse
     *
     * @param \Application\Entity\ClientHasCourse $clientHasCourse
     *
     * @return Prize
     */
    public function setClientHasCourse(\Application\Entity\ClientHasCourse $clientHasCourse = null)
    {
        $this->clientHasCourse = $clientHasCourse;

        return $this;
    }

    /**
     * Get clientHasCourse
     *
     * @return \Application\Entity\ClientHasCourse
     */
    public function getClientHasCourse()
    {
        return $this->clientHasCourse;
    }
}
