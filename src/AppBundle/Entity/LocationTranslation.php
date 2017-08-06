<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 7:58 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class LocationTranslation  implements \A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translation;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $locationHeading;

    /**
     * @return mixed
     */
    public function getLocationHeading()
    {
        return $this->locationHeading;
    }

    /**
     * @param mixed $locationHeading
     */
    public function setLocationHeading($locationHeading)
    {
        $this->locationHeading = $locationHeading;
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

    function __toString()
    {
        return (string)$this->getLocationHeading();
        // TODO: Implement __toString() method.
    }

    public function __call($method, $arguments)
    {
//        return $thi($method, $arguments);
    }

}