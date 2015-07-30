<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentHasAnsweredToItem
 *
 * @ORM\Table(name="student_has_answered_to_item", indexes={@ORM\Index(name="fk_student_has_answered_to_item_student_has_course_has_exam_idx", columns={"student_has_course_has_exam_id"}), @ORM\Index(name="fk_student_has_answered_to_item_item1_idx", columns={"item_id"}), @ORM\Index(name="fk_student_has_answered_to_item_option1_idx", columns={"option_id"})})
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
     * @var \Application\Entity\Item
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * })
     */
    private $item;

    /**
     * @var \Application\Entity\Option
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Option")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     * })
     */
    private $option;

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
     * Set option
     *
     * @param \Application\Entity\Option $option
     *
     * @return StudentHasAnsweredToItem
     */
    public function setOption(\Application\Entity\Option $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \Application\Entity\Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set studentHasCourseHasExam
     *
     * @param \Application\Entity\StudentHasCourseHasExam $studentHasCourseHasExam
     *
     * @return StudentHasAnsweredToItem
     */
    public function setStudentHasCourseHasExam(\Application\Entity\StudentHasCourseHasExam $studentHasCourseHasExam = null)
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
