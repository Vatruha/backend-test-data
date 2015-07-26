<?php

namespace AppBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=20, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40)
     */
    private $password;
    
    
     /**
      * @ORM\Column(type="string", length=32)
      */
      private $salt;
      
       /**
      * @ORM\Column(name="is_active", type="boolean")
      */
      private $isActive;
      
    /**
    * @var \Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\BasketItem>
    * @ORM\OneToMany(targetEntity="BasketItem", mappedBy="user")
    */
    private $basketItems;
    
   /**
    * @var \Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Order>
    * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
    */
    private $orders;
    
    

      public function __construct()
      {
          $this->isActive = true;
          $this->salt = md5(uniqid(null, true));
          $this->basketItems = new ArrayCollection();
          $this->orders = new ArrayCollection();
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
     * Get username
     *
     * @return string 
     */    
      public function getUsername()
      {
          return $this->username;
      }

      /**
     * Set login
     *
     * @param string $username
     * @return User
     */
      public function setUsername($username)
      {
          $this->username = $username;
          return $this;
      }
    /**
     * Set password
     *
     * @param string $password
     * @return User
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
      * @inheritDoc
      */
      public function getSalt()
      {
          return $this->salt;
      }

      public function setSalt($salt)
      {
          $this->salt = $salt;
      }
      
     /**
      * @inheritDoc
      */
      public function getRoles()
      {
          return array('ROLE_USER');
      }

      /**
      * @inheritDoc
      */
      public function eraseCredentials()
      {
      }
    
    /**
      * @see \Serializable::serialize()
      */
      public function serialize()
      {
          return serialize(
              array(
                  $this->id,
              )
          );
      }

      /**
      * @see \Serializable::unserialize()
      */
      public function unserialize($serialized)
      {
          list (
              $this->id,
              ) = unserialize($serialized);
      }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
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
     * Add basketItems
     *
     * @param \AppBundle\Entity\BasketItem $basketItems
     * @return User
     */
    public function addBasketItem(\AppBundle\Entity\BasketItem $basketItems)
    {
        $this->basketItems[] = $basketItems;

        return $this;
    }

    /**
     * Remove basketItems
     *
     * @param \AppBundle\Entity\BasketItem $basketItems
     */
    public function removeBasketItem(\AppBundle\Entity\BasketItem $basketItems)
    {
        $this->basketItems->removeElement($basketItems);
    }

    /**
     * Get basketItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBasketItems()
    {
        return $this->basketItems;
    }

    /**
     * Add orders
     *
     * @param \AppBundle\Entity\Order $orders
     * @return User
     */
    public function addOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \AppBundle\Entity\Order $orders
     */
    public function removeOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
