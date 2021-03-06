<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\User
 */
class User
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $passwordhash
     */
    private $passwordhash;

    /**
     * @var string $email
     */
    private $email = '';

    /**
     * @var string $fullname
     */
    private $fullname = '';

    /**
     * @var boolean $active
     */
    private $active = true;

    /**
     * @var boolean $admin
     */
    private $admin = false;


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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set passwordhash
     *
     * @param string $passwordhash
     * @return User
     */
    public function setPasswordhash($passwordhash)
    {
        $this->passwordhash = $passwordhash;
        return $this;
    }

    /**
     * Get passwordhash
     *
     * @return string 
     */
    public function getPasswordhash()
    {
        return $this->passwordhash;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $password_reset_emails;

    public function __construct()
    {
        $this->password_reset_emails = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add password_reset_emails
     *
     * @param rubikscomplex\model\UserPasswordEmail $passwordResetEmails
     * @return User
     */
    public function addUserPasswordEmail(\rubikscomplex\model\UserPasswordEmail $passwordResetEmails)
    {
        $this->password_reset_emails[] = $passwordResetEmails;
        return $this;
    }

    /**
     * Get password_reset_emails
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPasswordResetEmails()
    {
        return $this->password_reset_emails;
    }
}