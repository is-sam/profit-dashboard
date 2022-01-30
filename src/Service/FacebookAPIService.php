<?php

namespace App\Service;

use App\Entity\MarketingAccount;
use DateTime;
use Exception;
use FacebookAds\Object\AdAccount;

/**
 * Class FacebookAPIService.
 */
class FacebookAPIService extends AbstractService
{
    protected const FIELD_SPEND = 'spend';

    protected ?string $adAccountId;

    public function getAdSpendByDate(DateTime $dateStart, DateTime $dateEnd): ?float
    {
        if (!$adAccountId = $this->getLinkedAdAccount()) {
            return null;
        }

        $account = new AdAccount("act_$adAccountId");

        $fields = [
            self::FIELD_SPEND,
        ];

        $params = [
            'time_range' => [
                'since' => $dateStart->format('Y-m-d'),
                'until' => $dateEnd->format('Y-m-d'),
            ],
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

    public function getLinkedAdAccount(): ?string
    {
        /** @var MarketingAccountRepository $marketingAccountRepository */
        $marketingAccountRepository = $this->entityManager->getRepository(MarketingAccount::class);
        $marketingAccount = $marketingAccountRepository->findOneBySourceSlugAndShop('facebook-ads', $this->shop);

        $adAccountId = null;
        if ($marketingAccount) {
            $data = $marketingAccount->getData();
            $adAccountId = $data['account_id'] ?? null;
        }

        if (null === $adAccountId) {
            // throw new Exception("Facebook Account not linked");
        }

        return $adAccountId;
    }
}
