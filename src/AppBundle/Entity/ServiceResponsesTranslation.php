<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 7/30/2017
 * Time: 5:18 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ServiceResponsesTranslation implements \A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translation;

    /**
     * @ORM\Column(type="string")
     */
    protected $successMsg;
    /**
     * @ORM\Column(type="string")
     */
    protected $failureMsg;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $failureMsg1;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getSuccessMsg()
    {
        return $this->successMsg;
    }

    /**
     * @param mixed $successMsg
     */
    public function setSuccessMsg($successMsg)
    {
        $this->successMsg = $successMsg;
    }
   /**
     * @return mixed
     */
    public function getFailureMsg()
    {
        return $this->failureMsg;
    }

    /**
     * @param mixed $failureMsg
     */
    public function setFailureMsg($failureMsg)
    {
        $this->failureMsg = $failureMsg;
    }

    public function __call($method, $arguments)
    {
//        return $thi($method, $arguments);
    }

    /**
     * @return mixed
     */
    public function getFailureMsg1()
    {
        return $this->failureMsg1;
    }

    /**
     * @param mixed $failureMsg1
     */
    public function setFailureMsg1($failureMsg1)
    {
        $this->failureMsg1 = $failureMsg1;
    }
//    function __toString()
//    {
//        return (string)$this->getFirstName();
//        // TODO: Implement __toString() method.
//    }

}