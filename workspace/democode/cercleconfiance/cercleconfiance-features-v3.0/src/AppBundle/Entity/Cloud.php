<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Cloud
 *
 * @ORM\Table(name="cloud")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CloudRepository")
 */
class Cloud
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
     * @ORM\Column(name="file_name", type="string", length=255)
     * @Assert\File(maxSize = "20M",
     *     maxSizeMessage="Le fichier est trop volumineux")

     */
    private $fileName;

    /**
     * @ORM\OneToMany(targetEntity="DataApp", mappedBy="cloud")
     */
    private $dataApps;

    /**
     * @ORM\Column(name="file_type", type="string", length=255)
     *
     *
     */
    private $fileType;

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
     * @param string $fileName
     *
     * @return Cloud
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
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
     * @return Cloud
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


    /**
     * Set fileType
     *
     * @param string $fileType
     *
     * @return Cloud
     */
    public function setFileType($fileType)

    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set targetDir
     *
     * @param string $targetDir
     *
     * @return Cloud
     */

    public function setTargetDir($targetDir)
    {
        $this->targetDir = $targetDir;
        return $this;
    }

    /**
     * Get targetDir
     *
     * @return string
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
