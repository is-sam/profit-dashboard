<?php

namespace App\Service;

use DateTime;
use Exception;
use FacebookAds\Object\AdAccount;

/**
 * Class FacebookAPIService.
 */
class FacebookAPIService
{
    protected const FIELD_SPEND = 'spend';

    protected string $adAccountId;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->adAccountId = $this->getLinkedAdAccount();
    }

    public function getAdSpendByDate(DateTime $dateStart, DateTime $dateEnd): float
    {
        if (empty($this->adAccountId)) {
            return null;
        }

        $account = new AdAccount("act_$this->adAccountId");

        $fields = ['spend'];

        $params = [
            'time_range' => [
                'since' => $dateStart->format('Y-m-d'),
                'until' => $dateEnd->format('Y-m-d')
            ]
        ];

        $response = $account->getInsights($fields, $params)
            ->getResponse()
            ->getContent();

        if (!array_key_exists('data', $response)) {
            throw new Exception("key 'orders' not found in getOrders() response");
        }

        $spend = !empty($response['data']) ? $response['data'][0][self::FIELD_SPEND] : 0;

        return $spend;
    }

    public function getLinkedAdAccount(): string
    {
        return "323835781951033";
    }
}
