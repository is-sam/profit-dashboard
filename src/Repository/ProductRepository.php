<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByShop(string $shop): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.shop', 's')
            ->andWhere('s.url = :shop')
            ->setParameter('shop', $shop)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getProductsWithVariantsByShop(Shop $shop): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.shop', 's')
            ->join('p.variants', 'v', 'with', 'v.product = p.id')
            ->andWhere('s.url = :shop')
            ->setParameter('shop', $shop->getUrl())
            ->andWhere('p.status = :status')
            ->setParameter('status', Product::STATUS_ACTIVE)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOrphanVariantsByShop(Shop $shop): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.shop', 's')
            ->join('p.variants', 'v', 'with', 'v.product = p.id')
            ->andWhere('s.url = :shop')
            ->setParameter('shop', $shop->getUrl())
            ->andWhere('p.status = :status')
            ->setParameter('status', Product::STATUS_PLACEHOLDER)
            ->getQuery()
            ->getResult()
        ;
    }
}
