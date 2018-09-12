<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wall
 *
 * @ORM\Table(name="wall")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WallRepository")
 */
class Wall
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="DataApp", mappedBy="wall", cascade={"persist"}, fetch="EAGER")
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
     * Set content
     *
     * @param string $content
     *
     * @return Wall
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dataApps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add dataApp
     *
     * @param \AppBundle\Entity\DataApp $dataApp
     *
     * @return Wall
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
