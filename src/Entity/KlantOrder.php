<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="Klantaccount", inversedBy="bestellings")
     */
    private $klant;

    /**
     * @ORM\OneToOne(targetEntity="ObjectProductPeriod", inversedBy="klantOrder", cascade={"all"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $objectPeriod;

    /**
     * @ORM\OneToMany(targetEntity="OptionProductPeriod", mappedBy="klantOrder", cascade={"all"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $optionPeriods;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordernummer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDatum;

    public function __construct()
    {
        $this->orderDatum = new \DateTime('now');
        $this->ordernummer = random_int(1, 15);
        $this->optionPeriods = new ArrayCollection();
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
    public function getObjectPeriod()
    {
        return $this->objectPeriod;
    }

    /**
     * @param mixed $objectPeriod
     */
    public function setObjectPeriod($objectPeriod): void
    {
        $this->objectPeriod = $objectPeriod;
    }

    /**
     * @return mixed
     */
    public function getOptionPeriods()
    {
        return $this->optionPeriods;
    }

    public function addOptionPeriod(OptionProductPeriod $period){
        $this->optionPeriods[] = $period;
        $period->setKlantOrder($this);
        return $this;
    }

    public function removeOptionPeriod(OptionProductPeriod $period){
        $this->optionPeriods->removeElement($period);
    }
}
