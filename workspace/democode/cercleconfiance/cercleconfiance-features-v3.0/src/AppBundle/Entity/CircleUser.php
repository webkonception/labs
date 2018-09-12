<?php

namespace AppBundle\Entity;

use AppBundle\Repository\CircleUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * CircleUser
 *
 * @ORM\Table(name="circle_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CircleUserRepository")
 */
class CircleUser
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
     * @ORM\Column(name="admin_circle", type="boolean")
     */
    private $adminCircle=0;

    /**
     * @var bool
     *
     * @ORM\Column(name="circle_center", type="boolean")
     */
    private $circleCenter=1;

    /**
     * @var bool
     *
     * @ORM\Column(name="call_access", type="boolean")
     */

    private $callAccess=1;

    /**
     * @var bool
     *
     * @ORM\Column(name="wall_access", type="boolean")
     */

    private $wallAccess=1;

    /**
     * @var bool
     *
     * @ORM\Column(name="cloud_access", type="boolean")
     */

    private $cloudAccess=1;

    /**
     * @var bool
     *
     * @ORM\Column(name="agenda_access", type="boolean")
     */

    private $agendaAccess=1;

    /**
     * @ORM\OneToMany(targetEntity="ObjectEntry", mappedBy="circleUser", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private $objectEntries;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="circleUsers", cascade={"persist"}, fetch="EAGER")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Circle", inversedBy="circleUsers", cascade={"persist"}, fetch="EAGER")
     */
    private $circle;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\DataApp", mappedBy="circleUser", cascade={"persist"}, fetch="EAGER")
     */
    private $dataApps;

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
     * Set adminCircle
     *
     * @param boolean $adminCircle
     *
     * @return CircleUser
     */
    public function setAdminCircle($adminCircle)
    {
        $this->adminCircle = $adminCircle;

        return $this;
    }

    /**
     * Get adminCircle
     *
     * @return bool
     */
    public function getAdminCircle()
    {
        return $this->adminCircle;
    }

    /**
     * Set circleCenter
     *
     * @param boolean $circleCenter
     *
     * @return CircleUser
     */
    public function setCircleCenter($circleCenter)
    {
        $this->circleCenter = $circleCenter;

        return $this;
    }

    /**
     * Get circleCenter
     *
     * @return bool
     */
    public function getCircleCenter()
    {
        return $this->circleCenter;
    }

    /**
     * Set callAccess
     *
     * @param boolean $callAccess
     *
     * @return CircleUser
     */
    public function setCallAccess($callAccess)
    {
        $this->callAccess = $callAccess;

        return $this;
    }

    /**
     * Get callAccess
     *
     * @return bool
     */
    public function getCallAccess()
    {
        return $this->callAccess;
    }

    /**
     * Set wallAccess
     *
     * @param boolean $wallAccess
     *
     * @return CircleUser
     */
    public function setWallAccess($wallAccess)
    {
        $this->wallAccess = $wallAccess;

        return $this;
    }

    /**
     * Get wallAccess
     *
     * @return bool
     */
    public function getWallAccess()
    {
        return $this->wallAccess;
    }

    /**
     * Set cloudAccess
     *
     * @param boolean $cloudAccess
     *
     * @return CircleUser
     */
    public function setCloudAccess($cloudAccess)
    {
        $this->cloudAccess = $cloudAccess;

        return $this;
    }

    /**
     * Get cloudAccess
     *
     * @return bool
     */
    public function getCloudAccess()
    {
        return $this->cloudAccess;
    }

    /**
     * Set agendaAccess
     *
     * @param boolean $agendaAccess
     *
     * @return CircleUser
     */
    public function setAgendaAccess($agendaAccess)
    {
        $this->agendaAccess = $agendaAccess;

        return $this;
    }

    /**
     * Get agendaAccess
     *
     * @return bool
     */
    public function getAgendaAccess()
    {
        return $this->agendaAccess;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectEntries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add objectEntry
     *
     * @param \AppBundle\Entity\ObjectEntry $objectEntry
     *
     * @return CircleUser
     */
    public function addObjectEntry(\AppBundle\Entity\ObjectEntry $objectEntry)
    {
        $this->objectEntries[] = $objectEntry;

        return $this;
    }

    /**
     * Remove objectEntry
     *
     * @param \AppBundle\Entity\ObjectEntry $objectEntry
     */
    public function removeObjectEntry(\AppBundle\Entity\ObjectEntry $objectEntry)
    {
        $this->objectEntries->removeElement($objectEntry);
    }

    /**
     * Get objectEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectEntries()
    {
        return $this->objectEntries;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return CircleUser
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
     * Set address
     *
     * @param \UserBundle\Entity\Address $address
     *
     * @return CircleUser
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
     * Set circle
     *
     * @param \AppBundle\Entity\Circle $circle
     *
     * @return CircleUser
     */
    public function setCircle(\AppBundle\Entity\Circle $circle = null)
    {
        $this->circle = $circle;

        return $this;
    }

    /**
     * Get circle
     *
     * @return \AppBundle\Entity\Circle
     */
    public function getCircle()
    {
        return $this->circle;
    }

    /**
     * Add dataApp
     *
     * @param \AppBundle\Entity\DataApp $dataApp
     *
     * @return CircleUser
     */
    public function addDataApp(\AppBundle\Entity\DataApp $dataApp)
    {
        $this->dataApps[] = $dataApp;

        return $this;
    }

    /**
     * Remove dataApp
     *
     * @param \AppBundle\Entity\DataApp $dataApp
     */
    public function removeDataApp(\AppBundle\Entity\DataApp $dataApp)
    {
        $this->dataApps->removeElement($dataApp);
    }

    /**
     * Get dataApps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDataApps()
    {
        return $this->dataApps;
    }
}
