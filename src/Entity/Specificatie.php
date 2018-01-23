<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecificatieRepository")
 */
class Specificatie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ObjectProduct", mappedBy="specificatie")
     */
    private $object;

    /**
     * @ORM\Column(type="string")
     */
    private $merk;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $bouwjaar;

    /**
     * @ORM\Column(type="integer")
     */
    private $massaInventaris;

    /**
     * @ORM\Column(type="integer")
     */
    private $massaMax;

    /**
     * @ORM\Column(type="integer")
     */
    private $lengteTot;

    /**
     * @ORM\Column(type="integer")
     */
    private $lengteOpbouw;

    /**
     * @ORM\Column(type="integer")
     */
    private $hoogte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rijbewijsBenodigd;

    /**
     * @ORM\Column(type="decimal")
     */
    private $prijsDag;

    /**
     * @return mixed
     */
    public function getObjId()
    {
        return $this->objId;
    }

    /**
     * @param mixed $objId
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;
    }

    /**
     * @return mixed
     */
    public function getMerk()
    {
        return $this->merk;
    }

    /**
     * @param mixed $merk
     */
    public function setMerk($merk)
    {
        $this->merk = $merk;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getBouwjaar()
    {
        return $this->bouwjaar;
    }

    /**
     * @param mixed $bouwjaar
     */
    public function setBouwjaar($bouwjaar)
    {
        $this->bouwjaar = $bouwjaar;
    }

    /**
     * @return mixed
     */
    public function getMassaInventaris()
    {
        return $this->massaInventaris;
    }

    /**
     * @param mixed $massaInventaris
     */
    public function setMassaInventaris($massaInventaris)
    {
        $this->massaInventaris = $massaInventaris;
    }

    /**
     * @return mixed
     */
    public function getMassaMax()
    {
        return $this->massaMax;
    }

    /**
     * @param mixed $massaMax
     */
    public function setMassaMax($massaMax)
    {
        $this->massaMax = $massaMax;
    }

    /**
     * @return mixed
     */
    public function getLengteTot()
    {
        return $this->lengteTot;
    }

    /**
     * @param mixed $lengteTot
     */
    public function setLengteTot($lengteTot)
    {
        $this->lengteTot = $lengteTot;
    }

    /**
     * @return mixed
     */
    public function getLengteOpbouw()
    {
        return $this->lengteOpbouw;
    }

    /**
     * @param mixed $lengteOpbouw
     */
    public function setLengteOpbouw($lengteOpbouw)
    {
        $this->lengteOpbouw = $lengteOpbouw;
    }

    /**
     * @return mixed
     */
    public function getHoogte()
    {
        return $this->hoogte;
    }

    /**
     * @param mixed $hoogte
     */
    public function setHoogte($hoogte)
    {
        $this->hoogte = $hoogte;
    }

    /**
     * @return mixed
     */
    public function getRijbewijsBenodigd()
    {
        return $this->rijbewijsBenodigd;
    }

    /**
     * @param mixed $rijbewijsBenodigd
     */
    public function setRijbewijsBenodigd($rijbewijsBenodigd)
    {
        $this->rijbewijsBenodigd = $rijbewijsBenodigd;
    }

    /**
     * @return mixed
     */
    public function getPrijsDag()
    {
        return $this->prijsDag;
    }

    /**
     * @param mixed $prijsDag
     */
    public function setPrijsDag($prijsDag)
    {
        $this->prijsDag = $prijsDag;
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
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
}
