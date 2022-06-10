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

    public function save(array $data)
    {
        $profile = new ShippingProfile();
        $profile
            ->setShop($this->shop)
            ->setName($data['name'])
            ->setCost($data['cost']);

        if (!empty($data['countries'])) {
            $profile->setCountries($data['countries']);
        }

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variantId) {
                $variantRepository = $this->entityManager->getRepository(Variant::class);
                $variant = $variantRepository->find($variantId);
                $profile->addVariant($variant);
            }
            $profile->setIsVariantProfile(true);
        }

        $this->entityManager->persist($profile);
        $this->entityManager->flush();
    }

    public function remove(ShippingProfile $profile)
    {
        $this->entityManager->remove($profile);
        $this->entityManager->flush();
    }
}
