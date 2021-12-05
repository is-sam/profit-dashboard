<?php

namespace App\Service;

use App\Exception\ShopifySessionDataEmptyException;
use DateTime;
use Exception;
use Shopify\Clients\Rest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class ShopifyAdminAPIService.
 */
class ShopifyAdminAPIService
{
    public const ORDERS_TOTAL_PRICE = 'total_price';
    public const ORDERS_FINANCIAL_STATUS = 'financial_status';

    protected string|null $shop;
    protected string|null $accessToken;

    /**
     * Class constructor.
     */
    public function __construct(SessionInterface $session)
    {
        $this->shop = $session->get('shop');
        $this->accessToken = $session->get('accessToken');
    }

    public function getOrders(DateTime $dateStart = null, DateTime $dateEnd = null) : array
    {
        if (empty($this->shop) || empty($this->accessToken)) {
            throw new ShopifySessionDataEmptyException();
        }

        $client = new Rest($this->shop, $this->accessToken);

        $fields = [
            self::ORDERS_TOTAL_PRICE,
            self::ORDERS_FINANCIAL_STATUS
        ];

        $params = [
            'created_at_min' => $dateStart->format('c'),
            'created_at_max' => $dateEnd->format('c'),
            'status'         => 'any',
            'fields'         => implode(',', $fields)
        ];

        $response = $client->get("orders", [], $params)
            ->getDecodedBody();

        if (!array_key_exists('orders', $response)) {
            dd($response);
            throw new Exception("key 'orders' not found in getOrders() response");
        }

        $orders = $response['orders'];

        return $orders;
    }
}
