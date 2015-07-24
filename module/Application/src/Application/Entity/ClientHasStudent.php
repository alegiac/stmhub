<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientHasStudent
 *
 * @ORM\Table(name="client_has_student", indexes={@ORM\Index(name="fk_client_has_student_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_client_has_student_client1_idx", columns={"client_id"}), @ORM\Index(name="fk_client_has_student_activationstatus1_idx", columns={"activationstatus_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ClientHasStudentRepo")
 */
class ClientHasStudent
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
     * @var \Application\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

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
     * @return ClientHasStudent
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
     * @return ClientHasStudent
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
     * @return ClientHasStudent
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
     * Set student
     *
     * @param \Application\Entity\Student $student
     *
     * @return ClientHasStudent
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
}
