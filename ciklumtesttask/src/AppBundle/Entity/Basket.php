<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Basket
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BasketRepository")
 */
class Basket{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @var User
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="basket")
    **/
    protected $user;



    /**
    * @var \Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\BasketItem>
    * @ORM\OneToMany(targetEntity="BasketItem", mappedBy="basket")
    */
//    protected $items;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Basket
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\BasketItem $items
     * @return Basket
     */
    public function addItem(\AppBundle\Entity\BasketItem $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\BasketItem $items
     */
    public function removeItem(\AppBundle\Entity\BasketItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}
