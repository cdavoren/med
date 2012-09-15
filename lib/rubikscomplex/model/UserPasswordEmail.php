<?php

namespace rubikscomplex\model;

use Doctrine\ORM\Mapping as ORM;

/**
 * rubikscomplex\model\UserPasswordEmail
 */
class UserPasswordEmail
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $token
     */
    private $token;

    /**
     * @var datetime $sent_time
     */
    private $sent_time;

    /**
     * @var datetime $expiry_time
     */
    private $expiry_time;

    /**
     * @var rubikscomplex\model\User
     */
    private $user;


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
     * Set email
     *
     * @param string $email
     * @return UserPasswordEmail
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
     * Set token
     *
     * @param string $token
     * @return UserPasswordEmail
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set sent_time
     *
     * @param datetime $sentTime
     * @return UserPasswordEmail
     */
    public function setSentTime($sentTime)
    {
        $this->sent_time = $sentTime;
        return $this;
    }

    /**
     * Get sent_time
     *
     * @return datetime 
     */
    public function getSentTime()
    {
        return $this->sent_time;
    }

    /**
     * Set expiry_time
     *
     * @param datetime $expiryTime
     * @return UserPasswordEmail
     */
    public function setExpiryTime($expiryTime)
    {
        $this->expiry_time = $expiryTime;
        return $this;
    }

    /**
     * Get expiry_time
     *
     * @return datetime 
     */
    public function getExpiryTime()
    {
        return $this->expiry_time;
    }

    /**
     * Set user
     *
     * @param rubikscomplex\model\User $user
     * @return UserPasswordEmail
     */
    public function setUser(\rubikscomplex\model\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return rubikscomplex\model\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var boolean $used
     */
    private $used = false;


    /**
     * Set used
     *
     * @param boolean $used
     * @return UserPasswordEmail
     */
    public function setUsed($used)
    {
        $this->used = $used;
        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }
}
