<?php

namespace App\Service;

/**
 * Class DashboardService.
 */
class DashboardService
{
    public function calculateData(array $orders, int $fbAdSpend) : array
    {
        $ordersCount = $this->getOrdersCount($orders);
        $totalRevenue = $this->getTotalRevenue($orders);
        $averageOrderValue = $this->getAOV($ordersCount, $totalRevenue);
        $cpa = $this->getCPA($ordersCount, $fbAdSpend);
        $cogs = 0;
        $profit = $this->getProfit($totalRevenue, $cogs, $fbAdSpend);

        return [
            'orders'    => $ordersCount,
            'revenue'   => $totalRevenue,
            'aov'       => $averageOrderValue,
            'fbAdSpend' => $fbAdSpend,
            'cpa'       => $cpa,
            'cogs'      => $cogs,
            'profit'    => $profit
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
}
