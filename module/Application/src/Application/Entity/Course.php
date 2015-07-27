<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="course", indexes={@ORM\Index(name="fk_course_weekday1_idx", columns={"weekday_id"}), @ORM\Index(name="fk_course_activationstatus1_idx", columns={"activationstatus_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CourseRepo")
 */
class Course
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
     * @ORM\Column(name="periodicityweek", type="integer", nullable=false)
     */
    private $periodicityweek;

    /**
     * @var integer
     *
     * @ORM\Column(name="durationweek", type="integer", nullable=false)
     */
    private $durationweek;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

    /**
     * @var string
     *
     * @ORM\Column(name="emailtemplateurl", type="string", length=255, nullable=false)
     */
    private $emailtemplateurl;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalexams", type="integer", nullable=false)
     */
    private $totalexams = '0';

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
     * Set name
     *
     * @param string $name
     *
     * @return Course
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
     * @return Course
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
     * Set periodicityweek
     *
     * @param integer $periodicityweek
     *
     * @return Course
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
     * Set durationweek
     *
     * @param integer $durationweek
     *
     * @return Course
     */
    public function setDurationweek($durationweek)
    {
        $this->durationweek = $durationweek;

        return $this;
    }

    /**
     * Get durationweek
     *
     * @return integer
     */
    public function getDurationweek()
    {
        return $this->durationweek;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Course
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
     * Set emailtemplateurl
     *
     * @param string $emailtemplateurl
     *
     * @return Course
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
     * Set totalexams
     *
     * @param integer $totalexams
     *
     * @return Course
     */
    public function setTotalexams($totalexams)
    {
        $this->totalexams = $totalexams;

        return $this;
    }

    /**
     * Get totalexams
     *
     * @return integer
     */
    public function getTotalexams()
    {
        return $this->totalexams;
    }

    /**
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return Course
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
     * Set weekday
     *
     * @param \Application\Entity\Weekday $weekday
     *
     * @return Course
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
