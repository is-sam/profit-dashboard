<?php

namespace App\Service;

use App\Entity\CustomCost;
use App\Entity\ShippingProfile;
use App\Entity\Variant;
use DateTime;

/**
 * Class SettingsService.
 */
class SettingsService extends AbstractService
{
    public function saveCustomCost(array $data)
    {
        $customCost = new CustomCost();
        $customCost
            ->setShop($this->shop)
            ->setName($data['name'])
            ->setStartDate(new DateTime($data['start_date']))
            ->setAmount($data['amount'])
            ->setFrequency($data['frequency']);

        if (!empty($data['end_date'])) {
            $customCost->setEndDate(new DateTime($data['end_date']));
        }

        $this->entityManager->persist($customCost);
        $this->entityManager->flush();
    }

    public function deleteCustomCost(CustomCost $cost)
    {
        $this->entityManager->remove($cost);
        $this->entityManager->flush();
    }

    public function saveShippingProfile(array $data)
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

    public function deleteShippingProfile(ShippingProfile $profile)
    {
        $this->entityManager->remove($profile);
        $this->entityManager->flush();
    }
}
