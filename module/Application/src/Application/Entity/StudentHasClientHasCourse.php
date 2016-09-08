<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentHasClientHasCourse
 *
 * @ORM\Table(name="student_has_client_has_course", indexes={@ORM\Index(name="fk_student_has_client_has_course_client_has_course1_idx", columns={"client_has_course_id"}), @ORM\Index(name="fk_student_has_client_has_course_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_student_has_client_has_course_activationstatus1_idx", columns={"activationstatus_id"}), @ORM\Index(name="fk_student_has_client_has_course_student_has_course1_idx", columns={"student_has_course_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StudentHasClientHasCourseRepo")
 */
class StudentHasClientHasCourse
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
     * @var \Application\Entity\Activationstatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Activationstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activationstatus_id", referencedColumnName="id")
     * })
     */
    private $activationstatus;

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
     * @var \Application\Entity\Student
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

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
     * @return StudentHasClientHasCourse
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
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return StudentHasClientHasCourse
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
     * Set clientHasCourse
     *
     * @param \Application\Entity\ClientHasCourse $clientHasCourse
     *
     * @return StudentHasClientHasCourse
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

    /**
     * Set student
     *
     * @param \Application\Entity\Student $student
     *
     * @return StudentHasClientHasCourse
     */
    public function setStudent(\Application\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \Application\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set studentHasCourse
     *
     * @param \Application\Entity\StudentHasCourse $studentHasCourse
     *
     * @return StudentHasClientHasCourse
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
