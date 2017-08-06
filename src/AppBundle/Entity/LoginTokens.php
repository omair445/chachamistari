<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * LoginTokens
 *
 * @ORM\Table(name="login_tokens", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class LoginTokens
{
    /**
     * @var string
     *
     * @ORM\Column(name="token_key", type="string", length=255, nullable=false)
     */
    private $tokenKey;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $isEnabled;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var \AppBundle\Entity\AppUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AppUser",inversedBy="id")
     */
    private $user;
    /**
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }
    /**
     * @param string $tokenKey
     */
    public function setTokenKey($tokenKey)
    {
        $this->tokenKey = $tokenKey;
    }
    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }
    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }
    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return \AppBundle\Entity\AppUser
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param \AppBundle\Entity\AppUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

