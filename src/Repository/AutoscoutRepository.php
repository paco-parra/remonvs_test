<?php

namespace App\Repository;

use App\Entity\Autoscout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Autoscout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autoscout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autoscout[]    findAll()
 * @method Autoscout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoscoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autoscout::class);
    }

    // /**
    //  * @return Autoscout[] Returns an array of Autoscout objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Autoscout
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
