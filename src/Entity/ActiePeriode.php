<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 22/01/2018
 * Time: 12:07
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ActiePeriodeRepository")
 */
class ActiePeriode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     */
    private $actiePercentage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $actiePeriodeStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $actiePeriodeEinde;

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
    public function getActiePercentage()
    {
        return $this->actiePercentage;
    }

    /**
     * @param mixed $actiePercentage
     */
    public function setActiePercentage($actiePercentage)
    {
        $this->actiePercentage = $actiePercentage;
    }

    /**
     * @return mixed
     */
    public function getActiePeriodeStart()
    {
        return $this->actiePeriodeStart;
    }

    /**
     * @param mixed $actiePeriodeStart
     */
    public function setActiePeriodeStart($actiePeriodeStart)
    {
        $this->actiePeriodeStart = $actiePeriodeStart;
    }

    /**
     * @return mixed
     */
    public function getActiePeriodeEinde()
    {
        return $this->actiePeriodeEinde;
    }

    /**
     * @param mixed $actiePeriodeEinde
     */
    public function setActiePeriodeEinde($actiePeriodeEinde)
    {
        $this->actiePeriodeEinde = $actiePeriodeEinde;
    }
}