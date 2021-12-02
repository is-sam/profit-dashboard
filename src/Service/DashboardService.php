<?php

namespace App\Service;

/**
 * Class DashboardService.
 */
class DashboardService
{
    public function calculateData(array $orders) : array
    {
        $refundedOrders = array_filter($orders, fn ($order) => ($order['financial_status'] == 'refunded'));
        $ordersCount = count($orders);
        $totalRefunds = array_sum(array_column($refundedOrders, 'total_price'));
        $totalRevenue = array_sum(array_column($orders, 'total_price')) - $totalRefunds;
        $averageOrderValue = $ordersCount ? round($totalRevenue / $ordersCount, 2) : 0;

        return [
            'orders'    => $ordersCount,
            'revenue'   => $totalRevenue,
            'aov'       => $averageOrderValue
        ];
    }
}
