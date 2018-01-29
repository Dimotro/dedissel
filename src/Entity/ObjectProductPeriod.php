<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 26/01/2018
 * Time: 15:52
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectProductPeriodRepository")
 * @ORM\Table(name="object_product_period")
 */
class ObjectProductPeriod
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ObjectProduct", inversedBy="orderPeriods", cascade={"all"})
     */
    private $objectProduct;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\KlantOrder", inversedBy="objectPeriod", cascade={"all"})
     */
    private $klantOrder;

    public function __construct()
    {
//        $this->datumUit = new \DateTime('now');
//        $this->datumTerug = new \DateTime('now');
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
    public function getObjectProduct()
    {
        return $this->objectProduct;
    }

    /**
     * @param mixed $objectProduct
     */
    public function setObjectProduct($objectProduct): void
    {
        $this->objectProduct = $objectProduct;
    }

    /**
     * @return mixed
     */
    public function getKlantOrder()
    {
        return $this->klantOrder;
    }

    /**
     * @param mixed $klantOrder
     */
    public function setKlantOrder($klantOrder): void
    {
        $this->klantOrder = $klantOrder;
    }


}