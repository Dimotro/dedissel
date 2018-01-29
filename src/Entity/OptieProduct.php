<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptieRepository")
 */
class OptieProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $optieTitel;
    /**
     * @ORM\Column(type="string")
     */
    private $optieOmschrijving;

    /**
     * @ORM\Column(type="string")
     */
    private $optiePrijs;

    /**
     * @ORM\Column(type="array")
     */
    private $fotos;

    /**
     * @ORM\Column(type="string")
     */
    private $beschikbaarheid;

    /**
     * @ORM\OneToMany(targetEntity="OptionProductPeriod", mappedBy="optionProduct", cascade={"all"})
     */
    private $orderPeriods;

    public function __construct()
    {
        $this->beschikbaarheid = true;
        $this->orderPeriods = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getOptieOmschrijving()
    {
        return $this->optieOmschrijving;
    }

    /**
     * @param mixed $optieOmschrijving
     */
    public function setOptieOmschrijving($optieOmschrijving)
    {
        $this->optieOmschrijving = $optieOmschrijving;
    }

    /**
     * @return mixed
     */
    public function getOptiePrijs()
    {
        return $this->optiePrijs;
    }

    /**
     * @param mixed $optiePrijs
     */
    public function setOptiePrijs($optiePrijs)
    {
        $this->optiePrijs = $optiePrijs;
    }

    /**
     * @return mixed
     */
    public function getOptieDatumUit()
    {
        return $this->optieDatumUit;
    }

    /**
     * @param mixed $optieDatumUit
     */
    public function setOptieDatumUit($optieDatumUit)
    {
        $this->optieDatumUit = $optieDatumUit;
    }

    /**
     * @return mixed
     */
    public function getOptieDatumTerug()
    {
        return $this->optieDatumTerug;
    }

    /**
     * @param mixed $optieDatumTerug
     */
    public function setOptieDatumTerug($optieDatumTerug)
    {
        $this->optieDatumTerug = $optieDatumTerug;
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
    public function getKlantOrder()
    {
        return $this->klantOrder;
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
    public function getOptieTitel()
    {
        return $this->optieTitel;
    }

    /**
     * @param mixed $optieTitel
     */
    public function setOptieTitel($optieTitel)
    {
        $this->optieTitel = $optieTitel;
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
    public function setFotos($fotos): void
    {
        $this->fotos = $fotos;
    }

    /**
     * @return mixed
     */
    public function getOrderPeriods()
    {
        return $this->orderPeriods;
    }

    public function addOrderPeriod(OptionProductPeriod $date)
    {
        $this->orderPeriods[] = $date;
        $date->setOptionProduct($this);
        return $this;
    }

    public function removeOrderPeriod(OptionProductPeriod $date){
        $this->orderPeriods->removeElement($date);
    }
}
