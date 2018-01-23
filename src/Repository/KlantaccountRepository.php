<?php

namespace App\Repository;

use App\Entity\Klantaccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class KlantaccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        // Geeft aan aan welke entiteit deze repository gekoppeld kan worden in de constructor van de repository.
        parent::__construct($registry, Klantaccount::class);
    }
    // Controller voor het ophalen van gebruikers op basis van de verifactionToken
    public function getUserByVerifictionToken($token): array{
        // Haalt Query-builder op. Beschikbaar gesteld door 'extends ServiceEntityRepository' zet 'q' als representatie van de tabel
        $qb = $this->createQueryBuilder('q')
            // Expressie om op verificatietoken te filteren
            ->where('q.verificationToken = :token')
            // Zet het :token variabel naar de waarde van $token
            ->setParameter('token', $token)
            // Zet het maximum aantal records dat terug komt
            ->setMaxResults(1)
            // Bereid query voor executie
            ->getQuery();
        // Voert query uit en stuurt resultaat terug
        return $qb->execute();
    }
    // Controller voor het ophalen van gebruikers op basis van de forgetPassword token
    public function getUserByForgetToken($token) {
        // Haalt Query-builder op. Beschikbaar gesteld door 'extends ServiceEntityRepository' zet 'q' als representatie van de tabel
        $qb = $this->createQueryBuilder('q')
            // Expressie om op verificatietoken te filteren
            ->where('q.forgetToken = :token')
            // Zet het :token variabel naar de waarde van $token
            ->setParameter('token', $token)
            // Zet het maximum aantal records dat terug komt
            ->setMaxResults(1)
            // Bereid query voor executie
            ->getQuery();
        // Voert query uit en stuurt resultaat terug
        return $qb->execute();
    }
    // Controller voor het ophalen van gebruikers op basis van het emailadres van de klant
    public function getUserByEmail($email){
        // Haalt Query-builder op. Beschikbaar gesteld door 'extends ServiceEntityRepository' zet 'q' als representatie van de tabel
        $qb = $this->createQueryBuilder('q')
            // Expressie om op email te filteren
            ->where('q.email = :email')
            // Zet het :email variabel naar de waarde van $token
            ->setParameter('email', $email)
            // Bereid query voor executie
            ->getQuery();
        // Voert query uit en stuurt het eerste record terug. Veld is uniek volgens databaseregels, dus waardoor geen redunantie van gegevens plaatsvind / meerdere records terugkomen.
        return $qb->execute();
    }
}
