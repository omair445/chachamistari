<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use AppBundle\Password;
use AppBundle\VerificationCode;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 */
class AppUser
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translatable;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $email;
    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;
    /**
     * @ORM\Column(type="string",nullable=TRUE)
     */
    protected $userRole;
    /**
     * @ORM\Column(type="datetime",nullable=TRUE)
     */
    protected $created;

    /**
     * @ORM\Column(type="integer",nullable=true,length=6)
     */
    protected $verification_code;

  /**
     * @Assert\Valid
     */
    protected $translations;

    /**
     * @ORM\Column(type="bigint",nullable=true,length=15,unique=true)
     */
    protected $mobileNumber;
    /**
     * AppUser constructor.
     * @param $password
     */
    public function __construct()
    {
        $password = 123456;

        $this->setPassword($password);
        $this->setCreated(new \DateTime('now'));
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param mixed $userRole
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    function __toString()
    {
        return 'User';
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param mixed $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->verification_code;
    }

    /**
     * @param mixed $verification_code
     */
    public function setVerificationCode($verification_code)
    {
        $this->verification_code = $verification_code;
    }


}