<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 21/01/2018
 * Time: 13:35
 */

namespace App\Security;


use App\Entity\Klantaccount;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    // Beveiliginschecks die worden gedaan voor het geven van authenicatie aan een gebruiker (inloggen)
    // Hier wordt gekeken of de gebuiker geverifieerd is
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof Klantaccount){
            return;
        }
        if(!$user->getisVerified()){
            throw new AccountExpiredException('Uw account is nog niet geverifieerd, check uw email voor een verificatie-email. Het zou kunnen dat de emial terecht komt in uw spam-inbox.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }

}