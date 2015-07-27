<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam", indexes={@ORM\Index(name="fk_exam_course1_idx", columns={"course_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ExamRepo")
 */
class Exam
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
     * @var string
     *
     * @ORM\Column(name="imageurl", type="string", length=255, nullable=true)
     */
    private $imageurl;

    /**
     * @var integer
     *
     * @ORM\Column(name="points_if_completed", type="integer", nullable=false)
     */
    private $pointsIfCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="reduce_percentage_outtime", type="integer", nullable=true)
     */
    private $reducePercentageOuttime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="mandatory", type="integer", nullable=false)
     */
    private $mandatory;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalitems", type="integer", nullable=false)
     */
    private $totalitems;

    /**
     * @var integer
     *
     * @ORM\Column(name="prog_on_course", type="integer", nullable=false)
     */
    private $progOnCourse;

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
     * @return Exam
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
     * @return Exam
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
     * Set imageurl
     *
     * @param string $imageurl
     *
     * @return Exam
     */
    public function setImageurl($imageurl)
    {
        $this->imageurl = $imageurl;

        return $this;
    }

    /**
     * Get imageurl
     *
     * @return string
     */
    public function getImageurl()
    {
        return $this->imageurl;
    }

    /**
     * Set pointsIfCompleted
     *
     * @param integer $pointsIfCompleted
     *
     * @return Exam
     */
    public function setPointsIfCompleted($pointsIfCompleted)
    {
        $this->pointsIfCompleted = $pointsIfCompleted;

        return $this;
    }

    /**
     * Get pointsIfCompleted
     *
     * @return integer
     */
    public function getPointsIfCompleted()
    {
        return $this->pointsIfCompleted;
    }

    /**
     * Set reducePercentageOuttime
     *
     * @param integer $reducePercentageOuttime
     *
     * @return Exam
     */
    public function setReducePercentageOuttime($reducePercentageOuttime)
    {
        $this->reducePercentageOuttime = $reducePercentageOuttime;

        return $this;
    }

    /**
     * Get reducePercentageOuttime
     *
     * @return integer
     */
    public function getReducePercentageOuttime()
    {
        return $this->reducePercentageOuttime;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return Exam
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
     * Set mandatory
     *
     * @param integer $mandatory
     *
     * @return Exam
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return integer
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Set totalitems
     *
     * @param integer $totalitems
     *
     * @return Exam
     */
    public function setTotalitems($totalitems)
    {
        $this->totalitems = $totalitems;

        return $this;
    }

    /**
     * Get totalitems
     *
     * @return integer
     */
    public function getTotalitems()
    {
        return $this->totalitems;
    }

    /**
     * Set progOnCourse
     *
     * @param integer $progOnCourse
     *
     * @return Exam
     */
    public function setProgOnCourse($progOnCourse)
    {
        $this->progOnCourse = $progOnCourse;

        return $this;
    }

    /**
     * Get progOnCourse
     *
     * @return integer
     */
    public function getProgOnCourse()
    {
        return $this->progOnCourse;
    }

    /**
     * Set course
     *
     * @param \Application\Entity\Course $course
     *
     * @return Exam
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
}
