<?php

namespace App\Repository;

use App\Entity\MarketingAccount;
use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarketingAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketingAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketingAccount[]    findAll()
 * @method MarketingAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketingAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketingAccount::class);
    }

    // /**
    //  * @return MarketingAccount[] Returns an array of MarketingAccount objects
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

    public function findOneBySourceSlugAndShop(string $slug, Shop $shop): ?MarketingAccount
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.shop = :shop')
            ->setParameter('shop', $shop)
            ->join('m.marketingSource', 's')
            ->andWhere('s.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
