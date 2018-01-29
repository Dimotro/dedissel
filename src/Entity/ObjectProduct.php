<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectRepository")
 */
class ObjectProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Specificatie", inversedBy="object", cascade={"all"})
     */
    private $specificatie;

    /**
     * @ORM\OneToMany(targetEntity="ObjectProductPeriod", mappedBy="objectProduct", cascade={"all"})
     */
    private $orderPeriods;

    /**
     * @ORM\Column(type="string")
     */
    private $chassisnummer;

    /**
     * @ORM\Column(type="string")
     */
    private $kenteken;

    /**
     * @ORM\Column(type="string")
     */
    private $objNaam;

    /**
     * @ORM\Column(type="string")
     */
    private $objType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $beschikbaarheid;


    /**
     * @ORM\Column(type="text")
     */
    private $objOmschrijving;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     */
    private $prijs;


    /**
     * @ORM\Column(type="array")
     */
    private $fotos;

    public function __construct()
    {
        $this->orderPeriods = new ArrayCollection();
        $this->beschikbaarheid = true;
    }

    /**
     * @return mixed
     */
    public function getPrijs()
    {
        return $this->prijs;
    }

    /**
     * @param mixed $prijs
     */
    public function setPrijs($prijs)
    {
        $this->prijs = $prijs;
    }

    /**
     * @return mixed
     */
    public function getChassisnummer()
    {
        return $this->chassisnummer;
    }

    /**
     * @param mixed $chassisnummer
     */
    public function setChassisnummer($chassisnummer)
    {
        $this->chassisnummer = $chassisnummer;
    }

    /**
     * @return mixed
     */
    public function getKenteken()
    {
        return $this->kenteken;
    }

    /**
     * @param mixed $kenteken
     */
    public function setKenteken($kenteken)
    {
        $this->kenteken = $kenteken;
    }

    /**
     * @return mixed
     */
    public function getObjType()
    {
        return $this->objType;
    }

    /**
     * @param mixed $objType
     */
    public function setObjType($objType)
    {
        $this->objType = $objType;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSpecificatie()
    {
        return $this->specificatie;
    }

    /**
     * @param mixed $specificatie
     */
    public function setSpecificatie($specificatie)
    {
        $this->specificatie = $specificatie;
    }

    /**
     * @return mixed
     */
    public function getBeschikbaarheid()
    {
        return $this->beschikbaarheid;
    }

    /**
     * @param mixed $beschikbaarheid
     */
    public function setBeschikbaarheid($beschikbaarheid)
    {
        $this->beschikbaarheid = $beschikbaarheid;
    }

    /**
     * @return mixed
     */
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * @param mixed $fotos
     */
    public function setFotos($fotos)
    {
        $this->fotos = $fotos;
    }

    /**
     * @return mixed
     */
    public function getObjNaam()
    {
        return $this->objNaam;
    }

    /**
     * @param mixed $objNaam
     */
    public function setObjNaam($objNaam)
    {
        $this->objNaam = $objNaam;
    }

    /**
     * @return mixed
     */
    public function getObjOmschrijving()
    {
        return $this->objOmschrijving;
    }

    /**
     * @param mixed $objOmschrijving
     */
    public function setObjOmschrijving($objOmschrijving): void
    {
        $this->objOmschrijving = $objOmschrijving;
    }

    /**
     * @return mixed
     */
    public function getOrderPeriods()
    {
        return $this->orderPeriods;
    }

    public function addOrderPeriod(ObjectProductPeriod $period)
    {
        $this->orderPeriods[] = $period;
        $period->setObjectProduct($this);
        return $this;
    }

    public function removeOrderPeriod(ObjectProductPeriod $period)
    {
        $this->orderPeriods->removeElement($period);
    }
}
