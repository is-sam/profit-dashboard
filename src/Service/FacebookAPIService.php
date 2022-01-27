<?php

namespace App\Service;

use App\Repository\MarketingAccountRepository;
use DateTime;
use Exception;
use FacebookAds\Object\AdAccount;
use Symfony\Component\Security\Core\Security;

/**
 * Class FacebookAPIService.
 */
class FacebookAPIService
{
    protected const FIELD_SPEND = 'spend';

    protected ?string $adAccountId;

    /**
     * Class constructor.
     */
    public function __construct(Security $security, MarketingAccountRepository $marketingAccountRepository)
    {
        $shop = $security->getUser();
        $marketingAccount = $marketingAccountRepository->findOneBySourceSlugAndShop('facebook-ads', $shop);
        if ($marketingAccount) {
            $data = $marketingAccount->getData();
            $this->adAccountId = $data['account_id'] ?? null;
        }
    }

    public function getAdSpendByDate(DateTime $dateStart, DateTime $dateEnd): ?float
    {
        if (empty($this->adAccountId)) {
            return null;
        }

        $account = new AdAccount("act_$this->adAccountId");

        $fields = [
            self::FIELD_SPEND
        ];

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
            throw new Exception("key 'data' not found in getAdSpendByDate() response");
        }

        $spend = !empty($response['data']) ? $response['data'][0][self::FIELD_SPEND] : 0;

        return $spend;
    }

    public function getLinkedAdAccount(): string
    {
        return "323835781951033";
    }
}
