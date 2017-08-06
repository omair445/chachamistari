<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 11:33 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AreaTranslation  implements \A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translation;

    /**
     * @ORM\Column(type="string")
     */
    protected $areaName;

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @param mixed $areaName
     */
    public function setAreaName($areaName)
    {
        $this->areaName = $areaName;
    }

    function __toString()
    {
        return (string)$this->getAreaName();
        // TODO: Implement __toString() method.
    }

    public function __call($method, $arguments)
    {
//        return $thi($method, $arguments);
    }

}