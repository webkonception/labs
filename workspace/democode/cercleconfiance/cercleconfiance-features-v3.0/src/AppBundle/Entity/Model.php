<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model
 *
 * @ORM\Table(name="model")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelRepository")
 */
class Model
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
     * @ORM\Column(name="reference", type="string", length=45)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_url", type="string", length=255)
     */
    private $docUrl;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="models")
     */
    private $brand;

    /**
     * @ORM\OneToMany(targetEntity="DataObject", mappedBy="model", fetch="EAGER")
     */
    private $dataObjects;

    /**
     * @var string
     *
     * @ORM\Column(name="uniq_id", type="string")
     */
    private $uniqId;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="TypeObject", inversedBy="models", fetch="EAGER")
     */
    private $typeObject;

    /**
     * @ORM\OneToMany(targetEntity="ObjectEntry", mappedBy="model")
     */
    private $objectEntries;

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
     * Set reference
     *
     * @param string $reference
     *
     * @return Model
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Model
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
     * Set docUrl
     *
     * @param string $docUrl
     *
     * @return Model
     */
    public function setDocUrl($docUrl)
    {
        $this->docUrl = $docUrl;

        return $this;
    }

    /**
     * Get docUrl
     *
     * @return string
     */
    public function getDocUrl()
    {
        return $this->docUrl;
    }

    /**
     * Set brand
     *
     * @param \AppBundle\Entity\Brand $brand
     *
     * @return Model
     */
    public function setBrand(\AppBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \AppBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->connectedObjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Model
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set typeObject
     *
     * @param \AppBundle\Entity\TypeObject $typeObject
     *
     * @return Model
     */
    public function setTypeObject(\AppBundle\Entity\TypeObject $typeObject = null)
    {
        $this->typeObject = $typeObject;

        return $this;
    }

    /**
     * Get typeObject
     *
     * @return \AppBundle\Entity\TypeObject
     */
    public function getTypeObject()
    {
        return $this->typeObject;
    }

    /**
     * Add objectEntry
     *
     * @param \AppBundle\Entity\ObjectEntry $objectEntry
     *
     * @return Model
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
     * Set uniqId
     *
     * @param string $uniqId
     *
     * @return Model
     */
    public function setUniqId($uniqId)
    {
        $this->uniqId = $uniqId;

        return $this;
    }

    /**
     * Get uniqId
     *
     * @return string
     */
    public function getUniqId()
    {
        return $this->uniqId;
    }

    /**
     * Add dataObject
     *
     * @param \AppBundle\Entity\DataObject $dataObject
     *
     * @return Model
     */
    public function addDataObject(\AppBundle\Entity\DataObject $dataObject)
    {
        $this->dataObjects[] = $dataObject;

        return $this;
    }

    /**
     * Remove dataObject
     *
     * @param \AppBundle\Entity\DataObject $dataObject
     */
    public function removeDataObject(\AppBundle\Entity\DataObject $dataObject)
    {
        $this->dataObjects->removeElement($dataObject);
    }

    /**
     * Get dataObjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDataObjects()
    {
        return $this->dataObjects;
    }
}
