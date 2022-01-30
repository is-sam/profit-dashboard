<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ShopifyAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    protected ShopifyAuthHelper $shopifyAuthHelper;
    protected FlashBagInterface $flash;

    public function __construct(ShopifyAuthHelper $shopifyAuthHelper, RequestStack $requestStack)
    {
        $this->shopifyAuthHelper = $shopifyAuthHelper;
        $this->flash = $requestStack->getSession()->getBag('flashes');
    }

    public function supports(Request $request): ?bool
    {
        return $request->query->get('shop') and $request->query->get('hmac');
    }

    public function authenticate(Request $request): Passport
    {
        $params = $request->query->all();

        $this->shopifyAuthHelper->validateParams($params);

        $this->shopifyAuthHelper->createShopIfNotExists($params['shop']);

        return new SelfValidatingPassport(new UserBadge($params['shop']), []);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->flash->add('error', "Authentication failure: {$exception->getMessage()}");

        return new RedirectResponse('/auth/login');
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse('/auth/login');
    }
}
