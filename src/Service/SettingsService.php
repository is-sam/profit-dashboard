<?php

namespace App\Service;

use App\Entity\CustomCost;
use App\Entity\Shop;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class SettingsService.
 */
class SettingsService
{
    protected Shop $shop;
    protected EntityManagerInterface $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
    ) {
        $this->entityManager = $entityManager;
        $this->shop = $security->getUser();
    }

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
