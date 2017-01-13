<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Users
 *
 * @ORM\Table(name="simple_sf_users")
 * @ORM\Entity(repositoryClass="PublicBundle\Entity\UsersRepository")
 */
class Users implements UserInterface,AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="reg_time", type="string")
     */
    private $regTime;

    /**
     * @var string
     *
     * @ORM\Column(name="reg_ip", type="string")
     */
    private $regIp;

    /**
     * @var string
     *
     * @ORM\Column(name="last_login", type="string")
     */
    private $lastLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="last_ip", type="string")
     */
    private $lastIp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_first_login", type="boolean")
     */
    private $isFirstLogin;


    /**
     * 以下三个方法实现于 UserInterface
     *
     * 返回编码使用的盐
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * 返回用户的role
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * 删除用户中的敏感数据
     */
    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ) = unserialize($serialized);
    }

    /**
     * 以下四个方法实现于 AdvancedUserInterface
     *
     * 检查用户帐户是否已过期
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * 检查用户是否已被锁定
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * 检查用户凭据是否(密码)已过期
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * 检查用户是否已启用
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Users
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set regTime
     *
     * @param string $regTime
     * @return Users
     */
    public function setRegTime($regTime)
    {
        $this->regTime = $regTime;

        return $this;
    }

    /**
     * Get regTime
     *
     * @return string
     */
    public function getRegTime()
    {
        return $this->regTime;
    }

    /**
     * Set regIp
     *
     * @param string $regIp
     * @return Users
     */
    public function setRegIp($regIp)
    {
        $this->regIp = $regIp;

        return $this;
    }

    /**
     * Get regIp
     *
     * @return string
     */
    public function getRegIp()
    {
        return $this->regIp;
    }

    /**
     * Set lastLogin
     *
     * @param string $lastLogin
     * @return Users
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return string
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set lastIp
     *
     * @param string $lastIp
     * @return Users
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;

        return $this;
    }

    /**
     * Get lastIp
     *
     * @return string
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Users
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isFirstLogin
     *
     * @param boolean $isFirstLogin
     * @return Users
     */
    public function setIsFirstLogin($isFirstLogin)
    {
        $this->isFirstLogin = $isFirstLogin;

        return $this;
    }

    /**
     * Get isFirstLogin
     *
     * @return boolean
     */
    public function getIsFirstLogin()
    {
        return $this->isFirstLogin;
    }
}
