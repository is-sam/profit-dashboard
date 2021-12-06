<?php

namespace App\Repository;

use App\Entity\ShopifySession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopifySession|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopifySession|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopifySession[]    findAll()
 * @method ShopifySession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopifySessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopifySession::class);
    }

    // /**
    //  * @return ShopifySession[] Returns an array of ShopifySession objects
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
    public function findOneBySomeField($value): ?ShopifySession
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
