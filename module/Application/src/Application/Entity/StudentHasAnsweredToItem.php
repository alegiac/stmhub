<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentHasAnsweredToItem
 *
 * @ORM\Table(name="student_has_answered_to_item", indexes={@ORM\Index(name="fk_student_has_answered_to_item_student_has_course_has_exam_idx", columns={"student_has_course_has_exam_id"}), @ORM\Index(name="fk_student_has_answered_to_item_item1_idx", columns={"item_id"}), @ORM\Index(name="fk_student_has_answered_to_item_student_has_client_has_cour_idx", columns={"student_has_client_has_course_has_exam_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StudentHasAnsweredToItemRepo")
 */
class StudentHasAnsweredToItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="option_id", type="integer", nullable=true)
     */
    private $optionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false)
     */
    private $insertDate;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=65535, nullable=true)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="timeout", type="integer", nullable=false)
     */
    private $timeout = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="correct", type="integer", nullable=false)
     */
    private $correct = '0';

    /**
     * @var \Application\Entity\Item
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * })
     */
    private $item;

    /**
     * @var \Application\Entity\StudentHasClientHasCourseHasExam
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\StudentHasClientHasCourseHasExam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_has_client_has_course_has_exam_id", referencedColumnName="id")
     * })
     */
    private $studentHasClientHasCourseHasExam;

    /**
     * @var \Application\Entity\StudentHasCourseHasExam
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\StudentHasCourseHasExam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_has_course_has_exam_id", referencedColumnName="id")
     * })
     */
    private $studentHasCourseHasExam;



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
     * Set optionId
     *
     * @param integer $optionId
     *
     * @return StudentHasAnsweredToItem
     */
    public function setOptionId($optionId)
    {
        $this->optionId = $optionId;

        return $this;
    }

    /**
     * Get optionId
     *
     * @return integer
     */
    public function getOptionId()
    {
        return $this->optionId;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return StudentHasAnsweredToItem
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set insertDate
     *
     * @param \DateTime $insertDate
     *
     * @return StudentHasAnsweredToItem
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
     * Set value
     *
     * @param string $value
     *
     * @return StudentHasAnsweredToItem
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set timeout
     *
     * @param integer $timeout
     *
     * @return StudentHasAnsweredToItem
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get timeout
     *
     * @return integer
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set correct
     *
     * @param integer $correct
     *
     * @return StudentHasAnsweredToItem
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return integer
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set item
     *
     * @param \Application\Entity\Item $item
     *
     * @return StudentHasAnsweredToItem
     */
    public function setItem(\Application\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \Application\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set studentHasClientHasCourseHasExam
     *
     * @param \Application\Entity\StudentHasClientHasCourseHasExam $studentHasClientHasCourseHasExam
     *
     * @return StudentHasAnsweredToItem
     */
    public function setStudentHasClientHasCourseHasExam(StudentHasClientHasCourseHasExam $studentHasClientHasCourseHasExam = null)
    {
        $this->studentHasClientHasCourseHasExam = $studentHasClientHasCourseHasExam;

        return $this;
    }

    /**
     * Get studentHasClientHasCourseHasExam
     *
     * @return \Application\Entity\StudentHasClientHasCourseHasExam
     */
    public function getStudentHasClientHasCourseHasExam()
    {
        return $this->studentHasClientHasCourseHasExam;
    }

    /**
     * Set studentHasCourseHasExam
     *
     * @param \Application\Entity\StudentHasCourseHasExam $studentHasCourseHasExam
     *
     * @return StudentHasAnsweredToItem
     */
    public function setStudentHasCourseHasExam(StudentHasCourseHasExam $studentHasCourseHasExam = null)
    {
        $this->studentHasCourseHasExam = $studentHasCourseHasExam;

        return $this;
    }

    /**
     * Get studentHasCourseHasExam
     *
     * @return \Application\Entity\StudentHasCourseHasExam
     */
    public function getStudentHasCourseHasExam()
    {
        return $this->studentHasCourseHasExam;
    }
}
