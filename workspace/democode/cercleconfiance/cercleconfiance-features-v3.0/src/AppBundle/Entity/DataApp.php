<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataApp
 *
 * @ORM\Table(name="Data_app")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DataAppRepository")
 */
class DataApp
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
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**

     * @ORM\ManyToOne(targetEntity="CircleUser", inversedBy="dataApps")
     */
    private $circleUser;

    /**
     * @ORM\ManyToOne(targetEntity="Cloud", inversedBy="dataApps")
     */
    private $cloud;

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="dataApps", cascade={"persist", "remove"})
     */
    private $agenda;

    /**
     * @ORM\ManyToOne(targetEntity="Wall", inversedBy="dataApps", cascade={"persist"}, fetch="EAGER")
     */
    private $wall;

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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return DataApp
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->circles = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set cloud
     *
     * @param \AppBundle\Entity\Cloud $cloud
     *
     * @return DataApp
     */
    public function setCloud(\AppBundle\Entity\Cloud $cloud = null)
    {
        $this->cloud = $cloud;

        return $this;
    }

    /**
     * Get cloud
     *
     * @return \AppBundle\Entity\Cloud
     */
    public function getCloud()
    {
        return $this->cloud;
    }

    /**
     * Set agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     *
     * @return DataApp
     */
    public function setAgenda(\AppBundle\Entity\Agenda $agenda = null)
    {
        $this->agenda = $agenda;

        return $this;
    }

    /**
     * Get agenda
     *
     * @return \AppBundle\Entity\Agenda
     */
    public function getAgenda()
    {
        return $this->agenda;
    }

    /**
     * Set wall
     *
     * @param \AppBundle\Entity\Wall $wall
     *
     * @return DataApp
     */
    public function setWall(\AppBundle\Entity\Wall $wall = null)
    {
        $this->wall = $wall;

        return $this;
    }

    /**
     * Get wall
     *
     * @return \AppBundle\Entity\Wall
     */
    public function getWall()
    {
        return $this->wall;
    }


    /**
     * Set circleUser
     *
     * @param \AppBundle\Entity\CircleUser $circleUser
     *
     * @return DataApp
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
}
