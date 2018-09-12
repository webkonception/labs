<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agenda
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaRepository")
 */
class Agenda
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
     * @ORM\Column(name="eventId", type="string")
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="event_start", type="string", length=255)
     */
    private $eventStart;

    /**
     * @var string
     *
     * @ORM\Column(name="event_end", type="string", length=255)
     */
    private $eventEnd;

    /**
     * @ORM\OneToMany(targetEntity="DataApp", mappedBy="agenda", cascade={"persist", "remove"})
     */
    private $dataApps;

    /**
     * @ORM\ManyToOne(targetEntity="CategoryEvent", inversedBy="agendas")
     */
    private $categoryEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data_apps = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set eventId
     *
     * @param integer $eventId
     *
     * @return Agenda
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return integer
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Agenda
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Agenda
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set eventStart
     *
     * @param string $eventStart
     *
     * @return Agenda
     */
    public function setEventStart($eventStart)
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    /**
     * Get eventStart
     *
     * @return string
     */
    public function getEventStart()
    {
        return $this->eventStart;
    }

    /**
     * Set eventEnd
     *
     * @param string $eventEnd
     *
     * @return Agenda
     */
    public function setEventEnd($eventEnd)
    {
        $this->eventEnd = $eventEnd;

        return $this;
    }

    /**
     * Get eventEnd
     *
     * @return string
     */
    public function getEventEnd()
    {
        return $this->eventEnd;
    }

    /**
     * Add dataApp
     *
     * @param \AppBundle\Entity\DataApp $dataApp
     *
     * @return Agenda
     */
    public function addDataApp(\AppBundle\Entity\DataApp $dataApp)
    {
        $this->data_apps[] = $dataApp;

        return $this;
    }

    /**
     * Remove dataApp
     *
     * @param \AppBundle\Entity\DataApp $dataApp
     */
    public function removeDataApp(\AppBundle\Entity\DataApp $dataApp)
    {
        $this->data_apps->removeElement($dataApp);
    }

    /**
     * Get dataApps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDataApps()
    {
        return $this->data_apps;
    }

    /**
     * Set categoryEvent
     *
     * @param \AppBundle\Entity\CategoryEvent $categoryEvent
     *
     * @return Agenda
     */
    public function setCategoryEvent(\AppBundle\Entity\CategoryEvent $categoryEvent = null)
    {
        $this->category_event = $categoryEvent;

        return $this;
    }

    /**
     * Get categoryEvent
     *
     * @return \AppBundle\Entity\CategoryEvent
     */
    public function getCategoryEvent()
    {
        return $this->category_event;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Agenda
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
