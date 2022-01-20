<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * ShopifyAuthHelper
 */
class ShopifyAuthHelper
{
    protected string $key;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->key = $parameterBag->get('shopify.api.secret');
    }

    /**
     * Validate params returned by Shopify with HMAC
     *
     * @throws AuthenticationException
     * @param array $params
     * @return void
     */
    public function validateParams(array $params)
    {
        if (!array_key_exists('hmac', $params)) {
            throw new AuthenticationException("no HMAC provided");
        }

        $hmac = $params['hmac'];
        unset($params['hmac']);

        $hash = hash_hmac('sha256', http_build_query($params), $this->key);
        if ($hmac !== $hash) {
            throw new AuthenticationException("HMAC not valid");
        }
    }
}
