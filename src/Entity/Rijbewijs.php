<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RijbewijsRepository")
 */
class Rijbewijs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Klantgegeven", mappedBy="rijbewijs")
     */
    private $klantGegevens;

    /**
     * @ORM\Column(type="integer")
     */
    private $rijbewijsnummer;

    /**
     * @ORM\Column(type="array")
     */
    private $rijbewijsType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rijbewijsGeldigtot;


    /**
     * @ORM\Column(type="boolean")
     */
    private $rijbewijsGecontroleerd;

    public function __construct()
    {
        $this->testrit = new ArrayCollection();
        $this->rijbewijsType = array(
            'A' => false,
            'A1' => false,
            'A2' => false,
            'AM' => false,
            'B' => false,
            'BE' => false,
            'BPlus' => false,
            'C' => false,
            'CE' => false,
            'C1' => false,
            'C1E' => false,
            'D' => false,
            'DE' => false,
            'D1' => false,
            'D1E' => false,
            'T' => false,
        );
        $this->rijbewijsGecontroleerd = false;
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
    public function getRijbewijsnummer()
    {
        return $this->rijbewijsnummer;
    }

    /**
     * @param mixed $rijbewijsnummer
     */
    public function setRijbewijsnummer($rijbewijsnummer)
    {
        $this->rijbewijsnummer = $rijbewijsnummer;
    }

    /**
     * @return mixed
     */
    public function getRijbewijsType()
    {
        return $this->rijbewijsType;
    }

    /**
     * @param mixed $rijbewijsType
     */
    public function setRijbewijsType($rijbewijsType)
    {
        $this->rijbewijsType = $rijbewijsType;
    }

    /**
     * @return mixed
     */
    public function getRijbewijsGeldigtot()
    {
        return $this->rijbewijsGeldigtot;
    }

    /**
     * @param mixed $rijbewijsGeldigtot
     */
    public function setRijbewijsGeldigtot($rijbewijsGeldigtot)
    {
        $this->rijbewijsGeldigtot = $rijbewijsGeldigtot;
    }

    /**
     * @return mixed
     */
    public function getKlantGegevens()
    {
        return $this->klantGegevens;
    }

    /**
     * @param mixed $klantGegevens
     */
    public function setKlantGegevens($klantGegevens)
    {
        $this->klantGegevens = $klantGegevens;
    }

    /**
     * @return mixed
     */
    public function getRijbewijsGecontroleerd()
    {
        return $this->rijbewijsGecontroleerd;
    }

    /**
     * @param mixed $rijbewijsGecontroleerd
     */
    public function setRijbewijsGecontroleerd($rijbewijsGecontroleerd)
    {
        $this->rijbewijsGecontroleerd = $rijbewijsGecontroleerd;
    }

}
