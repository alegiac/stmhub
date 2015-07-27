<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamHasItem
 *
 * @ORM\Table(name="exam_has_item", indexes={@ORM\Index(name="fk_exam_has_item_item1_idx", columns={"item_id"}), @ORM\Index(name="fk_exam_has_item_exam1_idx", columns={"exam_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ExamHasItemRepo")
 */
class ExamHasItem
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
     * @var integer
     *
     * @ORM\Column(name="progressive", type="integer", nullable=false)
     */
    private $progressive = '0';

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
     * @var \Application\Entity\Item
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Item")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * })
     */
    private $item;



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
     * @return ExamHasItem
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
     * Set progressive
     *
     * @param integer $progressive
     *
     * @return ExamHasItem
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
     * Set exam
     *
     * @param \Application\Entity\Exam $exam
     *
     * @return ExamHasItem
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
     * Set item
     *
     * @param \Application\Entity\Item $item
     *
     * @return ExamHasItem
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
}
