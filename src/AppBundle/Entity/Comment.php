<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CommentRepository")
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     * @Assert\NotBlank()
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="upVote", type="integer")
     */
    private $upVote;

    /**
     * @var integer
     *
     * @ORM\Column(name="downVote", type="integer")
     */
    private $downVote;

    /**
    * @ORM\ManyToOne(targetEntity="Fortune", inversedBy="comments")
    **/
    private $fortune;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
    **/
    private $user;

    public function __construct()
    {
      $this->upVote=0;
      $this->downVote=0;
      $this->createdAt= new \DateTime();
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set upVote
     *
     * @param integer $upVote
     *
     * @return Comment
     */
    public function voteUp()
    {
        $this->upVote++;
        return $this;
    }

    /**
     * Get upVote
     *
     * @return integer
     */
    public function getUpVote()
    {
        return $this->upVote;
    }

    /**
     * Set downVote
     *
     * @param integer $downVote
     *
     * @return Comment
     */
    public function voteDown()
    {
        $this->downVote++;
        return $this;
    }

    /**
     * Get downVote
     *
     * @return integer
     */
    public function getDownVote()
    {
        return $this->downVote;
    }

    /**
     * Set fortune
     *
     * @param integer $fortune
     *
     * @return Comment
     */
    public function setFortune($fortune)
    {
        $this->fortune = $fortune;

        return $this;
    }

    /**
     * Get fortune
     *
     * @return integer
     */
    public function getFortune()
    {
        return $this->fortune;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}
