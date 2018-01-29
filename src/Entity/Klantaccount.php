<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KlantaccountRepository")
 */
class Klantaccount implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;


    /**
     * @ORM\Column(type="string")
     */
    private $verificationToken;

    /**
     * @ORM\Column(type="string")
     */
    private $forgetToken;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;


    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KlantOrder", mappedBy="klant")
     */
    private $bestellings;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Klantgegeven", inversedBy="klantAccount", cascade={"all"})
     */
    private $klantPersoonlijkeGegevens;

    public function __construct()
    {
        $this->bestellings = new ArrayCollection();
        $this->verificationToken = random_int(1, 990000000000);
        $this->forgetToken = random_int(1, 990000000000);
        $this->isActive = true;
        $this->isVerified = false;
    }

    public function addBestelling(KlantOrder $order)
    {
        $this->bestellings[] = $order;

        $order->setKlant($this);

        return $this;
    }
    public function removeBestelling(KlantOrder $order)
    {
        $this->bestellings->removeElement($order);
        return $this;
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
    public function getKlantPersoonlijkeGegevens()
    {
        return $this->klantPersoonlijkeGegevens;
    }

    /**
     * @param mixed $klantPersoonlijkeGegevens
     */
    public function setKlantPersoonlijkeGegevens($klantPersoonlijkeGegevens)
    {
        $this->klantPersoonlijkeGegevens = $klantPersoonlijkeGegevens;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
     * @param mixed $verificationToken
     */
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * @return mixed
     */
    public function getisVerified()
    {
        return $this->isVerified;
    }

    /**
     * @param mixed $isVerified
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;
    }


    public function getBestellings()
    {
        return $this->bestellings;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getForgetToken()
    {
        return $this->forgetToken;
    }

    /**
     * @param mixed $forgetToken
     */
    public function setForgetToken($forgetToken)
    {
        $this->forgetToken = $forgetToken;
    }



    public function getRoles()
    {
        if( $this->username == 'beheerder' ){
            return array('ROLE_ADMIN');
        } else{
            return array('ROLE_USER');
        }
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }
}
