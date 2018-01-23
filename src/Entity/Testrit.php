<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestritRepository")
 */
class Testrit{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rijbewijs", inversedBy="testrit")
     */
    private $rijbewijs;

    /**
     * @ORM\Column(type="integer")
     */
    private $rijbewijsnummer;

    /**
     * @ORM\Column(type="string")
     */
    private $rijbewijsType;

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
    public function getRijbewijs()
    {
        return $this->rijbewijs;
    }

    /**
     * @param mixed $rijbewijs
     */
    public function setRijbewijs($rijbewijs)
    {
        $this->rijbewijs = $rijbewijs;
    }

}
