<?php

namespace App\Repository;

use App\Entity\ActiePeriode;
use App\Entity\OptieProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OptieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OptieProduct::class);
    }

    public function getActualOptionPriceById($id){
        $sb = $this->createQueryBuilder('z')
            ->from('App:ActiePeriode', 'a')
            ->where('a.actiePeriodeEinde < CURRENT_DATE()')
            ->setMaxResults(1)
            ->getQuery();
        $actieperiode = $sb->execute();

        $qb = $this->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery();
        $optie = $qb->execute();

        if ($optie && $actieperiode) {
            return $optie->getPrijs() * (1 - $actieperiode->getActiePercentage);
        } else if ($optie){
            return $optie->getPrijs();
        }
    }
}
