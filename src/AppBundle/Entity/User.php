<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\OneToMany(targetEntity="Fortune", mappedBy="user")
    **/

    /**
    * @var string
    *
    * @ORM\Column(name="facebook_id", type="string")
     */
    protected $facebook_id;

    /**
    * @ORM\OneToMany(targetEntity="Fortune", mappedBy="user")
    **/

    private $fortunes;

    /**
    * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
    **/
    private $comments;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Get fortunes
     *
     * @return integer
     */
    public function getFortunes()
    {
        return $this->fortunes;
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
}
