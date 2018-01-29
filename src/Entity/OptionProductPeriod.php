<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 28/01/2018
 * Time: 11:52
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionProductPeriodRepository")
 * @ORM\Table(name="optie_product_period")
 */
class OptionProductPeriod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datumUit;

    /**
     * @ORM\Column(type="date")
     */
    private $datumTerug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OptieProduct", inversedBy="orderPeriods", cascade={"all"})
     */
    private $optionProduct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\KlantOrder", inversedBy="optionPeriods", cascade={"all"})
     */
    private $klantOrder;

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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDatumUit()
    {
        return $this->datumUit;
    }

    /**
     * @param mixed $datumUit
     */
    public function setDatumUit($datumUit): void
    {
        $this->datumUit = $datumUit;
    }

    /**
     * @return mixed
     */
    public function getDatumTerug()
    {
        return $this->datumTerug;
    }

    /**
     * @param mixed $datumTerug
     */
    public function setDatumTerug($datumTerug): void
    {
        $this->datumTerug = $datumTerug;
    }

    /**
     * @return mixed
     */
    public function getOptionProduct()
    {
        return $this->optionProduct;
    }

    /**
     * @param mixed $optionProduct
     */
    public function setOptionProduct($optionProduct): void
    {
        $this->optionProduct = $optionProduct;
    }

    /**
     * @return mixed
     */
    public function getKlantOrders()
    {
        return $this->klantOrder;
    }

    /**
     * @param mixed $klantOrders
     */
    public function setKlantOrder($klantOrder): void
    {
        $this->klantOrder = $klantOrder;
    }


}