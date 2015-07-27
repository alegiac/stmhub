<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentgroupHasStudent
 *
 * @ORM\Table(name="studentgroup_has_student", indexes={@ORM\Index(name="fk_studentgroup_has_student_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_studentgroup_has_student_studentgroup1_idx", columns={"studentgroup_id"}), @ORM\Index(name="fk_studentgroup_has_student_activationstatus1_idx", columns={"activationstatus_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StudentgroupHasStudentRepo")
 */
class StudentgroupHasStudent
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
     * @var \Application\Entity\Studentgroup
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Studentgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="studentgroup_id", referencedColumnName="id")
     * })
     */
    private $studentgroup;

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
     * @var \Application\Entity\Activationstatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Activationstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activationstatus_id", referencedColumnName="id")
     * })
     */
    private $activationstatus;



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
     * @return StudentgroupHasStudent
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
     * Set studentgroup
     *
     * @param \Application\Entity\Studentgroup $studentgroup
     *
     * @return StudentgroupHasStudent
     */
    public function setStudentgroup(\Application\Entity\Studentgroup $studentgroup = null)
    {
        $this->studentgroup = $studentgroup;

        return $this;
    }

    /**
     * Get studentgroup
     *
     * @return \Application\Entity\Studentgroup
     */
    public function getStudentgroup()
    {
        return $this->studentgroup;
    }

    /**
     * Set student
     *
     * @param \Application\Entity\Student $student
     *
     * @return StudentgroupHasStudent
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
     * Set activationstatus
     *
     * @param \Application\Entity\Activationstatus $activationstatus
     *
     * @return StudentgroupHasStudent
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
}
