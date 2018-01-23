<?php

namespace App\Repository;

use App\Entity\ActiePeriode;
use App\Entity\ObjectProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ObjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObjectProduct::class);
    }
    // Methode om de actuele prijs van een object op te kunnen  halen.
    public function getObjectPriceById($id){
        // Stel query builder op om query op te stellen
        $qb = $this->createQueryBuilder('q')
            ->where('q.id = :id')
            // Vul parameter :id in met $id
            ->setParameter('id', $id)
            // Zet maximale aantal resultaten
            ->setMaxResults(1)
            // Haal query op op basis van het vorige
            ->getQuery();
        // Voer query uit
        $object = $qb->execute();

        // Haal entiteit manager op met met huidige actieperiode
        $sb = $this->createQueryBuilder('q')
            ->from('App:ActiePeriode', 'a')
            ->where('a.actiePeriodeEinde < CURRENT_DATE()')
            ->getQuery();
        // Voer query uit
        $actieperiode = $sb->execute();
        // Check of actieperiode gevonden is
        if ($actieperiode) {
            // Haal beide gegeven op
            $actiepercentage = $actieperiode->getActiePercentage();
            $productPrice = $object->getPrice();
            // Reken actuele prijs uit
            $actualPrice = ($actiepercentage + 1) * $productPrice;
            return $actualPrice;
        } else{
            return null;
        }
    }
}
