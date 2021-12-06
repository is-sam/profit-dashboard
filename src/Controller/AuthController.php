<?php

namespace App\Controller;

use Shopify\Auth\OAuth;
use Shopify\Auth\OAuthCookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopify\Auth\FileSessionStorage;
use Shopify\Context;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AuthController.
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/auth/login/{shop}", name="auth_login")
     */
    public function auth(string $shop) {
        $oAuthResponse = OAuth::begin($shop, $this->generateUrl('auth_callback'), true, function (OAuthCookie $cookie) {
            $cookie = Cookie::create($cookie->getName())
                ->withValue($cookie->getValue())
                ->withExpires($cookie->getExpire())
                ->withSecure($cookie->isSecure())
                ->withHttpOnly($cookie->isSecure());

            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->sendHeaders();

            return true;
        });

        return new RedirectResponse($oAuthResponse);
    }

    /**
     * @Route("/auth/callback", name="auth_callback")
     */
    public function callback(Request $request, Session $session) {
        $shopifySession = OAuth::callback($request->cookies->all(), $request->query->all());

        $session->set('accessToken', $shopifySession->getAccessToken());
        $session->set('shop', $shopifySession->getShop());

        return new Response("<h3>Connected to Shopify</h3><br><button href='/'>Go Home</button>");
    }
}
