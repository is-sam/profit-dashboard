<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Shopify\Auth\OAuth;
use Shopify\Auth\OAuthCookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Http;
use Shopify\Context;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AuthController.
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/auth/login/{shop}", name="auth_login")
     */
    public function auth(string $shop) {
        $oAuthResponse = OAuth::begin($shop, $this->generateUrl('auth_callback'), true);

        return new RedirectResponse($oAuthResponse);
    }

    /**
     * @Route("/auth/callback", name="auth_callback")
     */
    public function callback(
        Request $request,
        ShopRepository $shopRepository,
        EntityManagerInterface $entityManager
    ) {
        // $shopifySession = OAuth::callback($request->cookies->all(), $request->query->all());
        $shop = $shopRepository->findOneBy(['url' => $request->query->get('shop')]);

        if (empty($shop)) {
            $shop = new Shop();
            $shop->setUrl($request->query->get('shop'));
        }

        $http = new Http($shop->getUrl());
        $response = $http->post("/admin/oauth/access_token", [
            'client_id'     =>	$this->getParameter('shopify.api.key'),
            'client_secret' =>	$this->getParameter('shopify.api.secret'),
            'code'          =>	$request->query->get('code')
        ]);

        $data = $response->getDecodedBody();

        $shop->setAccessToken($data['access_token']);

        $entityManager->persist($shop);
        $entityManager->flush();

        // $session = $request->getSession();
        // $session->set('accessToken', $shop->getAccessToken());
        // $session->set('shop', $shop->getUrl());

        return $this->redirectToRoute('home');
    }
}
