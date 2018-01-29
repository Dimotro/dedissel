<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 28/01/2018
 * Time: 14:10
 */

namespace App\Repository;


use App\Entity\ObjectProductPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ObjectProductPeriodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObjectProductPeriod::class);
    }

    public function isAvailibleAt($objectId ,$datumUit, $datumTerug){
        $query = $this->createQueryBuilder('qb')
            ->where('qb.objectProduct = :objectId')
            ->andWhere('qb.datumUit BETWEEN :datumUit AND :datumTerug')
            ->orWhere('qb.datumTerug BETWEEN :datumUit AND :datumTerug')
            ->andWhere('qb.objectProduct = :objectId')
            ->setParameter('objectId', $objectId)
            ->setParameter('datumUit', $datumUit)
            ->setParameter('datumTerug', $datumTerug)
            ->getQuery();
        $result = $query->execute();
        if ( !$result ){
            return true;
        }
        return false;
    }
}