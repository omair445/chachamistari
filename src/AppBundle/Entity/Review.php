<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="review")
 */
class Review
{


    /**
     * @ORM\Column(type="json_array")
     */
    private $obtRating;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="id")
     */
    private $company;

    /**
     * @ORM\Column(type="float")
     */
    private $percentage;

    /**
     * @return mixed
     */
    public function getObtRating()
    {
        return $this->obtRating;
    }

    /**
     * @param mixed $obtRating
     */
    public function setObtRating($obtRating)
    {
        $this->obtRating = $obtRating;
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
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param mixed $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }





}

