<?php
// src/Acme/UserBundle/Entity/User.php

namespace Sewolabs\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
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
     * @ORM\Column(type="string")
     */
	protected $facebookId;

	 /**
     * @ORM\Column(type="string")
     */
	protected $googleId;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

	/**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

	 /**
     * Sets the facebookId.
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }
}