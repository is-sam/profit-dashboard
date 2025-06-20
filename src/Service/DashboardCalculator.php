<?php

namespace App\Service;

use App\Entity\CustomCost;
use App\Entity\Variant;
use DateTime;
use Psr\Log\LoggerInterface;

/**
 * Class DashboardCalculator.
 */
class DashboardCalculator
{
    protected LoggerInterface $logger;

    /**
     * Class constructor.
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function calculateData(array $orders, array $variants, float $fbAdSpend, float $customCosts): array
    {
        $ordersCount = $this->getOrdersCount($orders);
        $totalRefunds = $this->getTotalRefundValue($orders);
        $totalRevenue = $this->getTotalRevenue($orders, $totalRefunds);
        $aov = $this->getAOV($ordersCount, $totalRevenue);
        $cogs = $this->getCostOfGoods($orders, $variants);
        $margin = $totalRevenue - $cogs;
        $marginRate = $totalRevenue ? $margin / $totalRevenue : 0;
        $totalAdSpend = $fbAdSpend;
        $cpa = $this->getCPA($ordersCount, $fbAdSpend);
        $roas = $this->getROAS($totalRevenue, $totalAdSpend);
        $profit = $this->getProfit($margin, $totalAdSpend, $customCosts);
        $profitRate = $totalRevenue ? $profit / $totalRevenue : 0;

        return [
            'orders' => $ordersCount,
            'revenue' => $totalRevenue,
            'refunds' => $totalRefunds,
            'aov' => $aov,
            'cogs' => $cogs,
            'margin' => $margin,
            'margin_rate' => $marginRate,
            'totalAdSpend' => $totalAdSpend,
            'fbAdSpend' => $fbAdSpend,
            'cpa' => $cpa,
            'roas' => $roas,
            'profit' => $profit,
            'profit_rate' => $profitRate,
            'custom_cost' => $customCosts,
        ];
    }

    /**
     * Undocumented function.
     *
     * @param CustomCost[] $customCosts
     */
    public function calculateCustomCosts(array $customCosts, DateTime $startDate, DateTime $endDate): float
    {
        $globalCost = 0;
        foreach ($customCosts as $customCost) {
            $intersectionStart = max($startDate, $customCost->getStartDate());
            $intersectionEnd = $customCost->getEndDate() ? min($endDate, $customCost->getEndDate()) : $endDate;
            if ($intersectionStart > $intersectionEnd) {
                continue;
            }

            $dateDiff = $intersectionStart->diff($intersectionEnd);
            $days = $dateDiff->days + 1;

            $cost = $this->getCustomCost($customCost, $days);
            $globalCost += $cost;

            $this->logger->info("CUSTOM COST {$customCost->getName()} $cost");
        }

        return $globalCost;
    }

    private function getCustomCost(CustomCost $customCost, int $days): float
    {
        if ($customCost->getFrequency() === CustomCost::FREQUENCY_ONETIME) {
            return $customCost->getAmount();
        }

        $frequencyToDayRatio = [
            CustomCost::FREQUENCY_DAILY => 1,
            CustomCost::FREQUENCY_WEEKLY => 7,
            CustomCost::FREQUENCY_MONTHLY => 30,
            CustomCost::FREQUENCY_QUARTERLY => 91,
            CustomCost::FREQUENCY_YEARLY => 365,
        ];

        return $customCost->getAmount() / $frequencyToDayRatio[$customCost->getFrequency()] * $days;
    }

    protected function getTotalRefundValue(array $orders): float
    {
        $refundedOrders = array_filter($orders, fn ($order) => ('refunded' == $order[ShopifyAdminAPIService::ORDERS_TOTAL_PRICE]));

        return array_sum(array_column($refundedOrders, ShopifyAdminAPIService::ORDERS_TOTAL_PRICE));
    }

    protected function getOrdersCount(array $orders): int
    {
        return count($orders);
    }

    protected function getTotalRevenue(array $orders, float $totalRefunds): float
    {
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

    protected function getProfit($margin, $adSpend, $costs): float
    {
        return $margin - $adSpend - $costs;
    }

    protected function getCostOfGoods(array $orders, array $variants): float
    {
        $cogs = 0;
        foreach ($orders as $order) {
            foreach ($order['line_items'] as $lineItem) {
                $variantId = $lineItem['variant_id'];
                $quantity = $lineItem['quantity'];

                if (null === $variantId) {
                    $cost = $this->getVariantCostByName($lineItem['name'], $variants);
                } else {
                    $cost = $this->getVariantCostById($variantId, $variants);
                }

                if (null !== $cost) {
                    $cogs += $cost * $quantity;
                }
            }
        }

        return (float) $cogs;
    }

    /**
     * Undocumented function.
     *
     * @param array<Variant> $variants
     */
    protected function getVariantCostById(string $variantId, array $variants): float
    {
        $variant = array_filter($variants, fn ($variant) => ($variant->getIdentifier() === $variantId));
        $variant = array_shift($variant);

        if (empty($variant)) {
            $this->logger->warning("Variant with id $variantId not found");

            return (float) 0;
        }

        return (float) $variant->getCost();
    }

    /**
     * Undocumented function.
     *
     * @param string         $variantId
     * @param array<Variant> $variants
     */
    protected function getVariantCostByName(string $name, array $variants): float
    {
        $variant = array_filter($variants, fn (Variant $variant) => ($variant->getTitle() === $name));
        $variant = array_shift($variant);

        if (empty($variant)) {
            $this->logger->warning("Variant with title $name not found");

            return (float) 0;
        }

        return (float) $variant->getCost();
    }

    protected function getROAS(float $revenue, float $adSpend): float
    {
        return $adSpend > 0 ? $revenue / $adSpend : 0;
    }
}
