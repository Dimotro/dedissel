<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 22/01/2018
 * Time: 14:25
 */

namespace App\Repository;


use App\Entity\ActiePeriode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ActiePeriodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActiePeriode::class);
    }
    public function getCurrentDiscount()
    {
        $query = $this->createQueryBuilder('qb')
            ->andWhere('CURRENT_DATE() BETWEEN qb.actiePeriodeStart AND qb.actiePeriodeEinde')
            ->setMaxResults(1)
            ->getQuery();
        return $query->execute();
    }
}


