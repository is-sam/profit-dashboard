<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class DashboardService.
 */
class DashboardService
{
    protected ShopifyAdminAPIService $adminAPI;
    protected EntityManagerInterface $entityManager;
    protected LoggerInterface $logger;

    /**
     * Class constructor.
     */
    public function __construct(
        ShopifyAdminAPIService $adminAPI,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->adminAPI = $adminAPI;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function calculateData(array $orders, array $variants, float $fbAdSpend) : array
    {
        $ordersCount = $this->getOrdersCount($orders);
        $totalRevenue = $this->getTotalRevenue($orders);
        $aov = $this->getAOV($ordersCount, $totalRevenue);
        $cogs = $this->getCostOfGoods($orders, $variants);
        $margin = $totalRevenue - $cogs;
        $totalAdSpend = $fbAdSpend;
        $cpa = $this->getCPA($ordersCount, $fbAdSpend);
        $roas = $this->getROAS($totalRevenue, $totalAdSpend);
        $profit = $this->getProfit($totalRevenue, $cogs, $totalAdSpend);

        return [
            'orders'    => $ordersCount,
            'revenue'   => $totalRevenue,
            'aov'       => $aov,
            'cogs'      => $cogs,
            'margin'    => $margin,
            'totalAdSpend' => $totalAdSpend,
            'fbAdSpend' => $fbAdSpend,
            'cpa'       => $cpa,
            'roas'      => $roas,
            'profit'    => $profit,
        ];
    }

    protected function getTotalRefundValue(array $orders): float
    {
        $refundedOrders = array_filter($orders, fn ($order) => ($order[ShopifyAdminAPIService::ORDERS_TOTAL_PRICE] == 'refunded'));

        return array_sum(array_column($refundedOrders, ShopifyAdminAPIService::ORDERS_TOTAL_PRICE));
    }

    protected function getOrdersCount(array $orders): int
    {
        return count($orders);
    }

    protected function getTotalRevenue(array $orders): float
    {
        $totalRefunds = $this->getTotalRefundValue($orders);
        return array_sum(array_column($orders, ShopifyAdminAPIService::ORDERS_TOTAL_PRICE)) - $totalRefunds;
    }

    protected function getAOV($ordersCount, $totalRevenue): float
    {
        return $ordersCount ? $totalRevenue / $ordersCount : 0;
    }

    protected function getCPA($ordersCount, $fbAdSpend): float
    {
        return $ordersCount ? $fbAdSpend / $ordersCount : 0;
    }

    protected function getProfit($totalRevenue, $cogs, $fbAdSpend): float
    {
        return $totalRevenue - $cogs - $fbAdSpend;
    }

    protected function getCostOfGoods(array $orders, array $variants): float
    {
        $cogs = 0;
        foreach ($orders as $order) {
            foreach ($order['line_items'] as $lineItem) {
                $variantId = $lineItem['variant_id'];
                $quantity = $lineItem['quantity'];

                if ($variantId === null) {
                    $name = $lineItem['name'];
                    $this->logger->info("Missing product variant: title=$name");
                    continue;
                }

                $cost = $this->getVariantCostById($variantId, $variants);

                if ($cost !== null) {
                    $cogs += $cost * $quantity;
                }
            }
        }

        return (float) $cogs;
    }

    /**
     * Undocumented function
     *
     * @param string $variantId
     * @param array<Variant> $variants
     * @return float
     */
    protected function getVariantCostById(string $variantId, array $variants): float
    {
        $variant = array_filter($variants, fn ($variant) => ($variant->getIdentifier() === $variantId));
        $variant = array_shift($variant);

        if (empty($variant)) {
            // throw new Exception("Variant with id $variantId not found");
            return (float) 0;
        }

        return (float) $variant->getCost();
    }

    protected function getROAS(float $revenue, float $adSpend): float
    {
        return $adSpend > 0 ? $revenue / $adSpend : 0;
    }
}
