<?php

namespace App\Security;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * ShopifyAuthHelper.
 */
class ShopifyAuthHelper
{
    protected string $key;
    protected EntityManagerInterface $entityManager;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->key = $parameterBag->get('shopify.api.secret');
        $this->entityManager = $entityManager;
    }

    /**
     * Validate params returned by Shopify with HMAC.
     *
     * @throws AuthenticationException
     *
     * @return void
     */
    public function validateParams(array $params)
    {
        if (!array_key_exists('hmac', $params)) {
            throw new AuthenticationException('no HMAC provided');
        }

        $hmac = $params['hmac'];
        unset($params['hmac']);

        $hash = hash_hmac('sha256', http_build_query($params), $this->key);
        if ($hmac !== $hash) {
            throw new AuthenticationException('HMAC not valid');
        }
    }

    public function createShopIfNotExists(string $url)
    {
        /** @var ShopRepository $shopRepository */
        $shop = $this->entityManager->getRepository(Shop::class)->findOneBy(['url' => $url]);

        if (empty($shop)) {
            $shop = new Shop();
            $shop->setUrl($url);
            $this->entityManager->persist($shop);
            $this->entityManager->flush();
        }
    }
}
