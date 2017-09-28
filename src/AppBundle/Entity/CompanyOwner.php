<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/29/17
 * Time: 12:00 AM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="company_owner")
 */
class CompanyOwner
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $status;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $cnic;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $age;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $homeTown;

    /**
     * CompanyOwner constructor.
     * @param $created
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->status= true;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getCnic()
    {
        return $this->cnic;
    }

    /**
     * @param mixed $cnic
     */
    public function setCnic($cnic)
    {
        $this->cnic = $cnic;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getHomeTown()
    {
        return $this->homeTown;
    }

    /**
     * @param mixed $homeTown
     */
    public function setHomeTown($homeTown)
    {
        $this->homeTown = $homeTown;
    }

    public function __toString()
    {
        if($this->getName()){
            return $this->getName();
        }else{
            return "New Company Owner";
        }

    }


}