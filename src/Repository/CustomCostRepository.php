<?php

namespace App\Repository;

use App\Entity\CustomCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomCost[]    findAll()
 * @method CustomCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomCost::class);
    }

    // /**
    //  * @return CustomCost[] Returns an array of CustomCost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomCost
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
