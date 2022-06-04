<?php

namespace App\Service;

use App\Entity\CustomCost;
use App\Entity\Variant;
use App\Repository\VariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class DashboardService.
 */
class DashboardService extends AbstractService
{
    protected DashboardCalculator $dashboardCalculator;
    protected ShopifyAdminAPIService $adminAPI;
    protected FacebookAPIService $facebookAPI;

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
        parent::__construct($entityManager, $security);
        $this->dashboardCalculator = $dashboardCalculator;
        $this->adminAPI = $adminAPI;
        $this->facebookAPI = $facebookAPI;
    }

    public function getData($dateStart, $dateEnd)
    {
        // REST call: get orders by date range
        $orders = $this->adminAPI->getOrders($dateStart, $dateEnd);
        // dd($dateStart, $dateEnd, $orders);

        // REST call: get Facebook Ad spend
        $fbAdSpend = $this->facebookAPI->getAdSpendByDate($dateStart, $dateEnd);
        $fbAdSpend = $fbAdSpend ?? 0;

        // Get variants
        /** @var VariantRepository $variantRepository */
        $variantRepository = $this->entityManager->getRepository(Variant::class);
        $variants = $variantRepository->getVariantsByShop($this->shop);

        // Get custom costs
        /** @var CustomCostRepository $customCostRepository */
        $customCostRepository = $this->entityManager->getRepository(CustomCost::class);
        $customCosts = $customCostRepository->findBy([
            'shop' => $this->shop,
        ]);
        $customCost = $this->dashboardCalculator->calculateCustomCosts($customCosts, $dateStart, $dateEnd);

        // calculate dashboard data
        $dashboard = $this->dashboardCalculator->calculateData($orders, $variants, $fbAdSpend, $customCost);

        return $dashboard;
    }
}
