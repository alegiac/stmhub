<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemHasAnswer
 *
 * @ORM\Table(name="item_has_answer", indexes={@ORM\Index(name="fk_item_has_answer_answer1_idx", columns={"answer_id"}), @ORM\Index(name="fk_item_has_answer_item1_idx", columns={"item_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ItemHasAnswerRepo")(repositoryClass="Application\Entity\Repository\ItemHasAnswerRepo")
 */
class ItemHasAnswer
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
     * @ORM\Column(name="correct", type="string", length=45, nullable=true)
     */
    private $correct;

    /**
     * @var \Application\Entity\Answer
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     * })
     */
    private $answer;

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
     * Set correct
     *
     * @param string $correct
     *
     * @return ItemHasAnswer
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return string
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set answer
     *
     * @param \Application\Entity\Answer $answer
     *
     * @return ItemHasAnswer
     */
    public function setAnswer(\Application\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Application\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set item
     *
     * @param \Application\Entity\Item $item
     *
     * @return ItemHasAnswer
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
