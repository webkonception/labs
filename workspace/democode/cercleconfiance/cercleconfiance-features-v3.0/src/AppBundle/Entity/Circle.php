<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Circle
 *
 * @ORM\Table(name="circle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CircleRepository")
 */
class Circle
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
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active=1;

    /**
     * @var bool
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid=1;

    /**
     * @ORM\ManyToOne(targetEntity="Offer", inversedBy="circles", cascade={"persist"})
     */
    private $offer;

    /**
     * @ORM\OneToMany(targetEntity="CircleUser", mappedBy="circle", cascade={"persist"}, fetch="EAGER")
     */
    private $circleUsers;

    /**
     * @ORM\ManyToOne(targetEntity="Address", inversedBy="circles", cascade={"persist"})
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez obligatoirement renseigner un nom.")
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="availabilityDate", type="datetime", nullable=true)
     */
    private $availabilityDate;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="number_circle_users", type="integer", length=11)
     * @Assert\Range(
     *      min = 2,
     *      max = 12,
     *      minMessage = "min 2 membres",
     *      maxMessage = "max 12 membres",
     *      invalidMessage = "Vous devez obligatoirement saisir un nombre entre 2 et 12"
     * )
     * @Assert\NotBlank(message="Vous devez obligatoirement saisir un nombre entre 2 et 12")
     */
    private $number_circle_users;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


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
     * Set active
     *
     * @param boolean $active
     *
     * @return Circle
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Circle
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return bool
     */
    public function getPaid()
    {
        return $this->paid;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->circle_users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set offer
     *
     * @param \AppBundle\Entity\Offer $offer
     *
     * @return Circle
     */
    public function setOffer(\AppBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \AppBundle\Entity\Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Add circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return Circle
     */
    public function addCircleUser(\AppBundle\Entity\CircleUser $circleUser)
    {
        $this->circle_users[] = $circleUser;

        return $this;
    }

    /**
     * Remove circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     */
    public function removeCircleUser(\AppBundle\Entity\CircleUser $circleUser)
    {
        $this->circle_users->removeElement($circleUser);
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
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return Circle
     */
    public function setAddress(\AppBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Circle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set availabilityDate
     *
     * @param \DateTime $availabilityDate
     *
     * @return Circle
     */
    public function setAvailabilityDate($availabilityDate)
    {
        $this->availabilityDate = $availabilityDate;

        return $this;
    }

    /**
     * Get availabilityDate
     *
     * @return \DateTime
     */
    public function getAvailabilityDate()
    {
        return $this->availabilityDate;
    }


    /**
     * Set NumberCircleUsers
     *
     * @param integer $number_circle_users
     *
     * @return Offer
     */
    public function setNumberCircleUsers($number_circle_users)
    {
        $this->number_circle_users = $number_circle_users;

        return $this;
    }

    /**
     * Get NumberCircleUsers
     *
     * @return integer
     */
    public function getNumberCircleUsers()
    {
        return $this->number_circle_users;
    }
}
