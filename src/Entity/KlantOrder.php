<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class KlantOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Klantaccount", inversedBy="bestellings", cascade={"all"})
     */
    private $klant;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordernummer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDatum;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ObjectProduct", inversedBy="klantOrder", cascade={"all"})
     */
    private $objectProduct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OptieProduct", inversedBy="", cascade={"all"})
     * @ORM\Column(nullable=true)
     */
    private $optieProducten;

    public function __construct()
    {
        $this->orderDatum = new \DateTime('now');
        $this->ordernummer = random_int(1, 15);
    }

    /**
     * @return mixed
     */
    public function getOrdernummer()
    {
        return $this->ordernummer;
    }

    /**
     * @param mixed $ordernummer
     */
    public function setOrdernummer($ordernummer)
    {
        $this->ordernummer = $ordernummer;
    }

    /**
     * @return mixed
     */
    public function getOrderDatum()
    {
        return $this->orderDatum;
    }

    /**
     * @param mixed $orderDatum
     */
    public function setOrderDatum($orderDatum)
    {
        $this->orderDatum = $orderDatum;
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
    public function getKlant()
    {
        return $this->klant;
    }

    /**
     * @param mixed $klant
     */
    public function setKlant($klant)
    {
        $this->klant = $klant;
    }

    /**
     * @return mixed
     */
    public function getObjectProduct()
    {
        return $this->objectProduct;
    }

    /**
     * @param mixed $objectProduct
     */
    public function setObjectProduct($objectProduct)
    {
        $this->objectProduct = $objectProduct;
    }

}
