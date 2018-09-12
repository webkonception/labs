<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use UserBundle\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     maxHeight="2048",
     *     maxWidth="2048",
     *     maxHeightMessage="La taille de votre image est trop grande, la hauteur maximum autorisée est de 2048px.",
     *     maxWidthMessage="La taille de votre image est trop grande, la largeur maximum autorisée est de 2048px."
     * )
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Entrez un nom s'il vous plait.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Le nom est trop court..",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */

    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Entrez un prénom s'il vous plait.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $firstname;

//    /**
//     * @ORM\Column(type="string", length=255)
//     *
//     * @Assert\NotBlank(message="choisir le lien principal avec le centre du cercle.", groups={"Registration", "Profile"})
//     * @Assert\Length(
//     *     min=3,
//     *     max=255,
//     *     minMessage="Le nom est trop court..",
//     *     maxMessage="Le nom est trop long.",
//     *     groups={"Registration", "Profile"}
//     * )
//     */
//    private $relation;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(
     *     min=0,
     *     max=10,
     *     minMessage="Le numéro de téléphone n'est pas complet.",
     *     maxMessage="Le numéro de téléphone est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\CircleUser", mappedBy="user", fetch="EAGER")
     */
    private $circleUsers;

        /**
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Address", inversedBy="users", cascade={"persist"})
     */
    private $address;

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
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set relation
     *
     * @param string $relation
     *
     * @return User
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get relation
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Add circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return User
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
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return User
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

}
