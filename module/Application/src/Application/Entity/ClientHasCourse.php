<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientHasCourse
 *
 * @ORM\Table(name="client_has_course", indexes={@ORM\Index(name="fk_client_has_course_course1_idx", columns={"course_id"}), @ORM\Index(name="fk_client_has_course_client1_idx", columns={"client_id"}), @ORM\Index(name="fk_client_has_course_activationstatus1_idx", columns={"activationstatus_id"}), @ORM\Index(name="fk_client_has_course_weekday1_idx", columns={"weekday_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ClientHasCourseRepo")
 */
class ClientHasCourse
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
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="periodicityweek", type="integer", nullable=true)
     */
    private $periodicityweek;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="emailtemplateurl", type="string", length=255, nullable=true)
     */
    private $emailtemplateurl;

    /**
     * @var \Application\Entity\Activationstatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Activationstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activationstatus_id", referencedColumnName="id")
     * })
     */
    private $activationstatus;

    /**
     * @var \Application\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \Application\Entity\Course
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Course")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;

    /**
     * @var \Application\Entity\Weekday
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Weekday")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="weekday_id", referencedColumnName="id")
     * })
     */
    private $weekday;



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
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return ClientHasCourse
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    /**
     * Get insertDate
     *
     * @return \DateTime
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return ClientHasCourse
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set periodicityweek
     *
     * @param integer $periodicityweek
     *
     * @return ClientHasCourse
     */
    public function setPeriodicityweek($periodicityweek)
    {
        $this->periodicityweek = $periodicityweek;

        return $this;
    }

    /**
     * Get periodicityweek
     *
     * @return integer
     */
    public function getPeriodicityweek()
    {
        return $this->periodicityweek;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ClientHasCourse
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
     * @return ClientHasCourse
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
     * Set emailtemplateurl
     *
     * @param string $emailtemplateurl
     *
     * @return ClientHasCourse
     */
    public function setEmailtemplateurl($emailtemplateurl)
    {
        $this->emailtemplateurl = $emailtemplateurl;

        return $this;
    }

    /**
     * Get emailtemplateurl
     *
     * @return string
     */
    public function getEmailtemplateurl()
    {
        return $this->emailtemplateurl;
    }

    /**
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return ClientHasCourse
     */
    public function setActivationstatus(\Application\Entity\Activationstatus $activationstatus = null)
    {
        $this->activationstatus = $activationstatus;

        return $this;
    }

    /**
     * Get activationstatus
     *
     * @return \Application\Entity\Activationstatus
     */
    public function getActivationstatus()
    {
        return $this->activationstatus;
    }

    /**
     * Set client
     *
     * @param \Application\Entity\Client $client
     *
     * @return ClientHasCourse
     */
    public function setClient(\Application\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Application\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set course
     *
     * @param \Application\Entity\Course $course
     *
     * @return ClientHasCourse
     */
    public function setCourse(\Application\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \Application\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set weekday
     *
     * @param \Application\Entity\Weekday $weekday
     *
     * @return ClientHasCourse
     */
    public function setWeekday(\Application\Entity\Weekday $weekday = null)
    {
        $this->weekday = $weekday;

        return $this;
    }

    /**
     * Get weekday
     *
     * @return \Application\Entity\Weekday
     */
    public function getWeekday()
    {
        return $this->weekday;
    }
}
