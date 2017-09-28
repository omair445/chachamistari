<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/7/2017
 * Time: 4:42 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translatable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $lat;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $longitude;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $imageUrl;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $phone;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $instagram;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $facebook;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $website;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $email;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;
    /**
     * @Assert\Valid
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location",inversedBy="id")
     */
    protected $location;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area",inversedBy="id")
     */
    protected $area;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category",inversedBy="id")
     */
    protected $service;

    /**
     * @ORM\Column(type="string",nullable=True)
     */
    protected $twitter;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $person;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    protected $startTime;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    protected $endTime;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $vistingCardImageUrl;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $shopage;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CompanyOwner",inversedBy="id")
     */
    protected $owner;
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getVistingCardImageUrl()
    {
        return $this->vistingCardImageUrl;
    }

    /**
     * @param mixed $vistingCardImageUrl
     */
    public function setVistingCardImageUrl($vistingCardImageUrl)
    {
        $this->vistingCardImageUrl = $vistingCardImageUrl;
    }

    /**
     * @return mixed
     */
    public function getShopage()
    {
        return $this->shopage;
    }

    /**
     * @param mixed $shopage
     */
    public function setShopage($shopage)
    {
        $this->shopage = $shopage;
    }


    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    public function __construct()
    {
        $this->setCreated(new \DateTime('now'));
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
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }


    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
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
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
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
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getCurrentTranslation()
    {
        return $this->getTranslations()->last();

    }

    function __toString()
    {
        return (string)$this->getCurrentTranslation();
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }




}