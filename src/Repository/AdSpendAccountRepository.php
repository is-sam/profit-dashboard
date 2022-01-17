<?php

namespace App\Repository;

use App\Entity\AdSpendAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdSpendAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdSpendAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdSpendAccount[]    findAll()
 * @method AdSpendAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdSpendAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdSpendAccount::class);
    }

    // /**
    //  * @return AdSpendAccount[] Returns an array of AdSpendAccount objects
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
    public function findOneBySomeField($value): ?AdSpendAccount
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
