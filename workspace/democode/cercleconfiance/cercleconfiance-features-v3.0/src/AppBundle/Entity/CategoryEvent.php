<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryEvent
 *
 * @ORM\Table(name="category_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryEventRepository")
 */
class CategoryEvent
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="color", type="string", length=10)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="categoryEvent")
     */
    private $agendas;


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
     * Set name
     *
     * @param string $name
     *
     * @return CategoryEvent
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
     * Set color
     *
     * @param integer $color
     *
     * @return CategoryEvent
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return int
     */
    public function getColor()
    {
        return $this->color;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agendas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     *
     * @return CategoryEvent
     */
    public function addAgenda(\AppBundle\Entity\Agenda $agenda)
    {
        $this->agendas[] = $agenda;

        return $this;
    }

    /**
     * Remove agenda
     *
     * @param \AppBundle\Entity\Agenda $agenda
     */
    public function removeAgenda(\AppBundle\Entity\Agenda $agenda)
    {
        $this->agendas->removeElement($agenda);
    }

    /**
     * Get agendas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgendas()
    {
        return $this->agendas;
    }
}
