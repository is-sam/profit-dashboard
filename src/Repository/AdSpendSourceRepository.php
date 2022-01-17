<?php

namespace App\Repository;

use App\Entity\AdSpendSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdSpendSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdSpendSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdSpendSource[]    findAll()
 * @method AdSpendSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdSpendSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdSpendSource::class);
    }

    // /**
    //  * @return AdSpendSource[] Returns an array of AdSpendSource objects
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
    public function findOneBySomeField($value): ?AdSpendSource
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
