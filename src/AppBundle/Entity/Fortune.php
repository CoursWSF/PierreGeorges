<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fortune
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FortuneRepository")
 */
class Fortune
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @var integer
     *
     * @ORM\Column(name="validate", type="integer")
     */
    private $validate;

    /**
    * @ORM\OneToMany(targetEntity="Comment", mappedBy="fortune")
    **/
    private $comments;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="fortunes")
    **/
    private $user;

    public function __construct()
    {
      $this->upVote=0;
      $this->downVote=0;
      $this->createdAt= new \DateTime();
      $this->validate= 0;
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
     * Set title
     *
     * @param string $title
     *
     * @return Fortune
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Fortune
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Fortune
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
     * @return Fortune
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
     * @return Fortune
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
     * Set validate
     *
     * @param integer $validate
     *
     * @return Fortune
     */
    public function setValidate()
    {
        $this->validate++;
        return $this;
    }

    /**
     * Get validate
     *
     * @return integer
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Get comments
     *
     * @return integer
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Fortune
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
