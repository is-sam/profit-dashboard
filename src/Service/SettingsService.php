<?php

namespace App\Service;

use App\Entity\CustomCost;
use DateTime;

/**
 * Class SettingsService.
 */
class SettingsService extends AbstractService
{
    public function saveCustomCost($data)
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
}
