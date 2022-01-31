<?php

namespace App\Repository;

use App\Entity\ShippingProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShippingProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingProfile[]    findAll()
 * @method ShippingProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingProfile::class);
    }

    // /**
    //  * @return ShippingProfile[] Returns an array of ShippingProfile objects
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
    public function findOneBySomeField($value): ?ShippingProfile
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
