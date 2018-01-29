<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 28/01/2018
 * Time: 14:12
 */

namespace App\Repository;


use App\Entity\OptionProductPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OptionProductPeriodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OptionProductPeriod::class);
    }

    public function getAvailibleOptions(){
        $currentDate = new \DateTime('now');
        $query = $this->createQueryBuilder('qb')
            ->where(':current NOT BETWEEN qb.datumUit AND qb.datumTerug')
            ->setParameter('current', $currentDate)
            ->getQuery();
        $result =$query->execute();
        $options = array();
        foreach ($result as $key => $option){
            $options[] = $result;
        }
        return $options;
    }
}