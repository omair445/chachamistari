<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CategoryTranslation implements \A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translation;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $catHeading;

    /**
     * @return mixed
     */
    public function getCatHeading()
    {
        return $this->catHeading;
    }

    /**
     * @param mixed $catHeading
     */
    public function setCatHeading($catHeading)
    {
        $this->catHeading = $catHeading;
    }

    public function __call($method, $arguments)
    {
//        return $thi($method, $arguments);
    }
    function __toString()
    {
        return (string)$this->getCatHeading();
        // TODO: Implement __toString() method.
    }







}