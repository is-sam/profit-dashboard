<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ShopifyRedirectAuthenticator extends AbstractAuthenticator
{
    protected ShopifyAuthHelper $shopifyAuthHelper;

    public function __construct(ShopifyAuthHelper $shopifyAuthHelper)
    {
        $this->shopifyAuthHelper = $shopifyAuthHelper;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'home' and $request->query->get('hmac') !== null;
    }

    public function authenticate(Request $request): Passport
    {
        $params = $request->query->all();

        $this->shopifyAuthHelper->validateParams($params);

        $passport = new SelfValidatingPassport(new UserBadge($params['shop']), []);

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse('/auth/login');
    }

}
