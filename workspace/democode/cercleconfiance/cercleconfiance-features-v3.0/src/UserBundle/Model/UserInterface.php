<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 30/05/17
 * Time: 17:52
 */

namespace UserBundle\Model;

use UserBundle\Entity\User;

interface UserInterface extends \FOS\UserBundle\Model\UserInterface
{
    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar);

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname);

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname();

    /**
     * Set relation
     *
     * @param string $relation
     *
     * @return User
     */
    public function setRelation($relation);

    /**
     * Get relation
     *
     * @return string
     */
    public function getRelation();

    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber();

    /**
     * Add circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return User
     */
    public function addCircleUser(\AppBundle\Entity\CircleUser $circleUser);

    /**
     * Remove circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     */
    public function removeCircleUser(\AppBundle\Entity\CircleUser $circleUser);

    /**
     * Get circleUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCircleUsers();

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return User
     */
    public function setAddress(\AppBundle\Entity\Address $address = null);

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address
     */
    public function getAddress();
}