<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AddressRepository")
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="\UserBundle\Entity\User", mappedBy="address", cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Circle", mappedBy="address", cascade={"persist"})
     */
    private $circles;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->circleUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return Address
     */
    public function addCircleUser(\AppBundle\Entity\CircleUser $circleUser)
    {
        $this->circleUsers[] = $circleUser;

        return $this;
    }

    /**
     * Remove circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     */
    public function removeCircleUser(\AppBundle\Entity\CircleUser $circleUser)
    {
        $this->circleUsers->removeElement($circleUser);
    }

    /**
     * Get circleUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCircleUsers()
    {
        return $this->circleUsers;
    }

    /**
     * Add circle
     *
     * @param \AppBundle\Entity\Circle $circle
     *
     * @return Address
     */
    public function addCircle(\AppBundle\Entity\Circle $circle)
    {
        $this->circles[] = $circle;

        return $this;
    }

    /**
     * Remove circle
     *
     * @param \AppBundle\Entity\Circle $circle
     */
    public function removeCircle(\AppBundle\Entity\Circle $circle)
    {
        $this->circles->removeElement($circle);
    }

    /**
     * Get circles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCircles()
    {
        return $this->circles;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Address
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Address
     */
    public function addUser(\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \UserBundle\Entity\User $user
     */
    public function removeUser(\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
