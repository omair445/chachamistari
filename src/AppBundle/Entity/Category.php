<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 5:41 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translatable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $catIconPath;
    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    protected $created;


    /**
     * @Assert\Valid
     */
    protected $translations;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * Category constructor.
     * @param $created
     */
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
    public function getCatIconPath()
    {
        return $this->catIconPath;
    }

    /**
     * @param mixed $catIconPath
     */
    public function setCatIconPath($catIconPath)
    {
        $this->catIconPath = $catIconPath;
    }


    public function getCurrentTranslation()
    {
        if($this->getTranslations()){
            return $this->getTranslations()->last();
        }else{
            return "Category";
        }
//        return $this->getTranslations()->last();
//        dump($this->getTranslations()->last());die;

    }

    function __toString()
    {
        if($this->getCurrentTranslation()){
            return (string)$this->getCurrentTranslation();
        }else{
            return "Category";
        }
//        return (string)$this->getCurrentTranslation();
//        dump($this->getCurrentTranslation());die;
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



    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }


}