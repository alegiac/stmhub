<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentHasClientHasCourseHasExam
 *
 * @ORM\Table(name="student_has_client_has_course_has_exam", indexes={@ORM\Index(name="fk_student_has_client_has_course_has_exam_exam1_idx", columns={"exam_id"}), @ORM\Index(name="fk_student_has_client_has_course_has_exam_student_has_clien_idx", columns={"student_has_client_has_course_id"}), @ORM\Index(name="fk_student_has_client_has_course_has_exam_student_has_cours_idx", columns={"student_has_course_has_exam_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StudentHasClientHasCourseHasExamRepo")
 */
class StudentHasClientHasCourseHasExam
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
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
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
     * @var integer
     *
     * @ORM\Column(name="progressive", type="integer", nullable=false)
     */
    private $progressive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expected_end_date", type="datetime", nullable=true)
     */
    private $expectedEndDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="mandatory", type="integer", nullable=true)
     */
    private $mandatory;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notified_date", type="datetime", nullable=true)
     */
    private $notifiedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="real_start_date", type="datetime", nullable=true)
     */
    private $realStartDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="session_on_course", type="integer", nullable=true)
     */
    private $sessionOnCourse;

    /**
     * @var string
     *
     * @ORM\Column(name="session_on_exam", type="string", length=45, nullable=true)
     */
    private $sessionOnExam;

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
     * @var \Application\Entity\StudentHasClientHasCourse
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\StudentHasClientHasCourse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_has_client_has_course_id", referencedColumnName="id")
     * })
     */
    private $studentHasClientHasCourse;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Item", inversedBy="studentHasClientHasCourseHasExam")
     * @ORM\JoinTable(name="student_has_client_has_course_has_exam_has_item",
     *   joinColumns={
     *     @ORM\JoinColumn(name="student_has_client_has_course_has_exam_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     *   }
     * )
     */
    private $item;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * @return StudentHasClientHasCourseHasExam
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
     * Set progressive
     *
     * @param integer $progressive
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setProgressive($progressive)
    {
        $this->progressive = $progressive;

        return $this;
    }

    /**
     * Get progressive
     *
     * @return integer
     */
    public function getProgressive()
    {
        return $this->progressive;
    }

    /**
     * Set expectedEndDate
     *
     * @param \DateTime $expectedEndDate
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setExpectedEndDate($expectedEndDate)
    {
        $this->expectedEndDate = $expectedEndDate;

        return $this;
    }

    /**
     * Get expectedEndDate
     *
     * @return \DateTime
     */
    public function getExpectedEndDate()
    {
        return $this->expectedEndDate;
    }

    /**
     * Set mandatory
     *
     * @param integer $mandatory
     *
     * @return StudentHasClientHasCourseHasExam
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
     * Set notifiedDate
     *
     * @param \DateTime $notifiedDate
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setNotifiedDate($notifiedDate)
    {
        $this->notifiedDate = $notifiedDate;

        return $this;
    }

    /**
     * Get notifiedDate
     *
     * @return \DateTime
     */
    public function getNotifiedDate()
    {
        return $this->notifiedDate;
    }

    /**
     * Set realStartDate
     *
     * @param \DateTime $realStartDate
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setRealStartDate($realStartDate)
    {
        $this->realStartDate = $realStartDate;

        return $this;
    }

    /**
     * Get realStartDate
     *
     * @return \DateTime
     */
    public function getRealStartDate()
    {
        return $this->realStartDate;
    }

    /**
     * Set sessionOnCourse
     *
     * @param integer $sessionOnCourse
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setSessionOnCourse($sessionOnCourse)
    {
        $this->sessionOnCourse = $sessionOnCourse;

        return $this;
    }

    /**
     * Get sessionOnCourse
     *
     * @return integer
     */
    public function getSessionOnCourse()
    {
        return $this->sessionOnCourse;
    }

    /**
     * Set sessionOnExam
     *
     * @param string $sessionOnExam
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setSessionOnExam($sessionOnExam)
    {
        $this->sessionOnExam = $sessionOnExam;

        return $this;
    }

    /**
     * Get sessionOnExam
     *
     * @return string
     */
    public function getSessionOnExam()
    {
        return $this->sessionOnExam;
    }

    /**
     * Set exam
     *
     * @param \Application\Entity\Exam $exam
     *
     * @return StudentHasClientHasCourseHasExam
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
     * Set studentHasClientHasCourse
     *
     * @param \Application\Entity\StudentHasClientHasCourse $studentHasClientHasCourse
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function setStudentHasClientHasCourse(\Application\Entity\StudentHasClientHasCourse $studentHasClientHasCourse = null)
    {
        $this->studentHasClientHasCourse = $studentHasClientHasCourse;

        return $this;
    }

    /**
     * Get studentHasClientHasCourse
     *
     * @return \Application\Entity\StudentHasClientHasCourse
     */
    public function getStudentHasClientHasCourse()
    {
        return $this->studentHasClientHasCourse;
    }

    /**
     * Set studentHasCourseHasExam
     *
     * @param \Application\Entity\StudentHasCourseHasExam $studentHasCourseHasExam
     *
     * @return StudentHasClientHasCourseHasExam
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

    /**
     * Add item
     *
     * @param \Application\Entity\Item $item
     *
     * @return StudentHasClientHasCourseHasExam
     */
    public function addItem(\Application\Entity\Item $item)
    {
        $this->item[] = $item;
        return $this;
    }

    /**
     * Remove item
     *
     * @param \Application\Entity\Item $item
     */
    public function removeItem(\Application\Entity\Item $item)
    {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItem()
    {
        return $this->item;
    }
}
