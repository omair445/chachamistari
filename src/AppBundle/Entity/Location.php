<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 7:55 AM
 */

namespace AppBundle\Entity;


use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="locations")
 */
class Location
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translatable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @Assert\Valid
     */
    protected $translations;

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
      return (string)$this->getLocationHeading($this);
    }

    public function getLocationHeading($object){
       return $object->getCurrentTranslation()->getTranslatable()->getTranslations()->getValues()[1]->getLocationHeading();
    }


}