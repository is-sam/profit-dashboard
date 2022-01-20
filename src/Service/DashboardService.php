<?php

namespace App\Service;

use App\Entity\Shop;
use App\Entity\Variant;
use App\Repository\VariantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class DashboardService.
 */
class DashboardService
{
    protected Shop $shop;
    protected DashboardCalculator $dashboardCalculator;
    protected ShopifyAdminAPIService $adminAPI;
    protected FacebookAPIService $facebookAPI;
    protected EntityManager $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(
        DashboardCalculator $dashboardCalculator,
        ShopifyAdminAPIService $adminAPI,
        FacebookAPIService $facebookAPI,
        EntityManagerInterface $entityManager,
        Security $security,
    ) {
        $this->dashboardCalculator = $dashboardCalculator;
        $this->adminAPI = $adminAPI;
        $this->facebookAPI = $facebookAPI;
        $this->entityManager = $entityManager;
        $this->shop = $security->getUser();
    }

    public function getData($dateStart, $dateEnd)
    {
        // REST call: get orders by date range
        $orders = $this->adminAPI->getOrders($dateStart, $dateEnd);

        // REST call: get Facebook Ad spend
        $fbAdSpend = $this->facebookAPI->getAdSpendByDate($dateStart, $dateEnd);

        // calculate dashboard data
        /** @var VariantRepository $variantRepository */
        $variantRepository = $this->entityManager->getRepository(Variant::class);
        $variants = $variantRepository->getVariantsByShop($this->shop);
        $dashboard = $this->dashboardCalculator->calculateData($orders, $variants, $fbAdSpend);

        return $dashboard;
    }
}
