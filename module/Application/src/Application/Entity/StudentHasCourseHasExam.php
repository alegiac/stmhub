<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentHasCourseHasExam
 *
 * @ORM\Table(name="student_has_course_has_exam", indexes={@ORM\Index(name="fk_student_has_course_has_exam_exam1_idx", columns={"exam_id"}), @ORM\Index(name="fk_student_has_course_has_exam_student_has_course1_idx", columns={"student_has_course_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StudentHasCourseHasExamRepo")
 */
class StudentHasCourseHasExam
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
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="completed", type="integer", nullable=false)
     */
    private $completed;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=true)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=45, nullable=false)
     */
    private $token;

    /**
     * @var \Application\Entity\Exam
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     * })
     */
    private $exam;

    /**
     * @var \Application\Entity\StudentHasCourse
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\StudentHasCourse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_has_course_id", referencedColumnName="id")
     * })
     */
    private $studentHasCourse;



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
     * @return StudentHasCourseHasExam
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
     * @return StudentHasCourseHasExam
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
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return StudentHasCourseHasExam
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set completed
     *
     * @param integer $completed
     *
     * @return StudentHasCourseHasExam
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return integer
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return StudentHasCourseHasExam
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
     * Set answer
     *
     * @param string $answer
     *
     * @return StudentHasCourseHasExam
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return StudentHasCourseHasExam
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set exam
     *
     * @param \Application\Entity\Exam $exam
     *
     * @return StudentHasCourseHasExam
     */
    public function setExam(\Application\Entity\Exam $exam = null)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam
     *
     * @return \Application\Entity\Exam
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set studentHasCourse
     *
     * @param \Application\Entity\StudentHasCourse $studentHasCourse
     *
     * @return StudentHasCourseHasExam
     */
    public function setStudentHasCourse(\Application\Entity\StudentHasCourse $studentHasCourse = null)
    {
        $this->studentHasCourse = $studentHasCourse;

        return $this;
    }

    /**
     * Get studentHasCourse
     *
     * @return \Application\Entity\StudentHasCourse
     */
    public function getStudentHasCourse()
    {
        return $this->studentHasCourse;
    }
}
