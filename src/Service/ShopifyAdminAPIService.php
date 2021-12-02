<?php

namespace App\Service;

use DateTime;
use Exception;
use Shopify\Clients\Rest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class ShopifyAdminAPIService.
 */
class ShopifyAdminAPIService
{
    protected string $shop;
    protected string $accessToken;

    /**
     * Class constructor.
     */
    public function __construct(SessionInterface $session)
    {
        $this->shop = $session->get('shop');
        $this->accessToken = $session->get('accessToken');

        if (empty($this->shop) || empty($this->accessToken)) {
            throw new Exception("Session data empty !");
        }
    }

    public function getOrders(DateTime $dateStart = null, DateTime $dateEnd = null) : array
    {
        $client = new Rest($this->shop, $this->accessToken);
        $response = $client->get("orders", [], [
            'created_at_min' => $dateStart->format('c'),
            'created_at_max' => $dateEnd->format('c'),
            'status'         => 'any'
        ])->getDecodedBody();

        if (!array_key_exists('orders', $response)) {
            throw new Exception("key 'orders' not found in getOrders() response");
        }

        $orders = $response['orders'];

        return $orders;
    }
}
