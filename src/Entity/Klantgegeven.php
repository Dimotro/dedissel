<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KlantgegevenRepository")
 */

class Klantgegeven
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Klantaccount")
     */
    private $klantAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Klantadres")
     */
    private $klantNAW;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Rijbewijs")
     */
    private $rijbewijs;

    /**
     * @ORM\Column(type="string")
     */
    private $klantVoorletters;

    /**
     * @ORM\Column(type="string")
     */
    private $klantVoornaam;

    /**
     * @ORM\Column(type="string")
     */
    private $klantTussenvoegsel;

    /**
     * @ORM\Column(type="string")
     */
    private $klantAchternaam;

    /**
     * @ORM\Column(type="string")
     */
    private $klantTelefoon;

    /**
     * @ORM\Column(type="string")
     */
    private $klantMobiel;

    /**
     * @return mixed
     */
    public function getKlantVoorletters()
    {
        return $this->klantVoorletters;
    }

    /**
     * @param mixed $klantVoorletters
     */
    public function setKlantVoorletters($klantVoorletters)
    {
        $this->klantVoorletters = $klantVoorletters;
    }

    /**
     * @return mixed
     */
    public function getKlantTussenvoegsel()
    {
        return $this->klantTussenvoegsel;
    }

    /**
     * @param mixed $klantTussenvoegsel
     */
    public function setKlantTussenvoegsel($klantTussenvoegsel)
    {
        $this->klantTussenvoegsel = $klantTussenvoegsel;
    }

    /**
     * @return mixed
     */
    public function getKlantAchternaam()
    {
        return $this->klantAchternaam;
    }

    /**
     * @param mixed $klantAchternaam
     */
    public function setKlantAchternaam($klantAchternaam)
    {
        $this->klantAchternaam = $klantAchternaam;
    }

    /**
     * @return mixed
     */
    public function getKlantTelefoon()
    {
        return $this->klantTelefoon;
    }

    /**
     * @param mixed $klantTelefoon
     */
    public function setKlantTelefoon($klantTelefoon)
    {
        $this->klantTelefoon = $klantTelefoon;
    }

    /**
     * @return mixed
     */
    public function getKlantMobiel()
    {
        return $this->klantMobiel;
    }

    /**
     * @param mixed $klantMobiel
     */
    public function setKlantMobiel($klantMobiel)
    {
        $this->klantMobiel = $klantMobiel;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPersoonlijkeGegevens()
    {
        return $this->persoonlijkeGegevens;
    }

    /**
     * @return mixed
     */
    public function getKlantAccount()
    {
        return $this->klantAccount;
    }

    /**
     * @param mixed $klantAccount
     */
    public function setKlantAccount($klantAccount)
    {
        $this->klantAccount = $klantAccount;
    }

    /**
     * @return mixed
     */
    public function getKlantNAW()
    {
        return $this->klantNAW;
    }

    /**
     * @param mixed $klantNAW
     */
    public function setKlantNAW($klantNAW)
    {
        $this->klantNAW = $klantNAW;
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

    /**
     * @return mixed
     */
    public function getKlantVoornaam()
    {
        return $this->klantVoornaam;
    }

    /**
     * @param mixed $klantVoornaam
     */
    public function setKlantVoornaam($klantVoornaam)
    {
        $this->klantVoornaam = $klantVoornaam;
    }


}
