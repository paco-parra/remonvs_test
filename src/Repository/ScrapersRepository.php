<?php

namespace App\Repository;

use App\Entity\Scrapers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Scrapers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scrapers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scrapers[]    findAll()
 * @method Scrapers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScrapersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scrapers::class);
    }

    // /**
    //  * @return Scrapers[] Returns an array of Scrapers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scrapers
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
