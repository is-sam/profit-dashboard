<?php

namespace App\Repository;

use App\Entity\MarketingSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarketingSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketingSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketingSource[]    findAll()
 * @method MarketingSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketingSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketingSource::class);
    }

    // /**
    //  * @return MarketingSource[] Returns an array of MarketingSource objects
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
    public function findOneBySomeField($value): ?MarketingSource
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
