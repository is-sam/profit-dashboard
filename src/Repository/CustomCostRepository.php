<?php

namespace App\Repository;

use App\Entity\CustomCost;
use App\Entity\Shop;
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

    public function save(CustomCost $customCost, Shop $shop)
    {
        $customCost->setShop($shop);
        if ($customCost->getFrequency() === CustomCost::FREQUENCY_ONETIME) {
            $customCost->setEndDate($customCost->getStartDate());
        }

        $this->_em->persist($customCost);
        $this->_em->flush();
    }

    public function remove(CustomCost $cost)
    {
        $this->_em->remove($cost);
        $this->_em->flush();
    }
}
