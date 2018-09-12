<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectEntry
 *
 * @ORM\Table(name="object_entry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObjectEntryRepository")
 */
class ObjectEntry
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
     * @ORM\Column(name="access", type="boolean")
     */
    private $access=0;

    /**
     * @ORM\ManyToOne(targetEntity="CircleUser", inversedBy="objectEntries", cascade={"persist", "remove"})
     */
    private $circleUser;

    /**
     * @ORM\ManyToOne(targetEntity="Model", inversedBy="objectEntries", fetch="EAGER")
     */
    private $model;


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
     * Set access
     *
     * @param boolean $access
     *
     * @return ObjectEntry
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return bool
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return ObjectEntry
     */
    public function setCircleUser(\AppBundle\Entity\CircleUser $circleUser = null)
    {
        $this->circleUser = $circleUser;

        return $this;
    }

    /**
     * Get circleUser
     *
     * @return \AppBundle\Entity\CircleUser
     */
    public function getCircleUser()
    {
        return $this->circleUser;
    }


    /**
     * Set model
     *
     * @param \AppBundle\Entity\Model $model
     *
     * @return ObjectEntry
     */
    public function setModel(\AppBundle\Entity\Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return \AppBundle\Entity\Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
