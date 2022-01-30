<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Form\Type\ShopifyLoginType;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Shopify\Auth\OAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopify\Clients\Http;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class AuthController.
 */
class AuthController extends AbstractController
{

    #[Route('/auth/login', name: 'auth_login_page')]
    public function login(Request $request)
    {
        $loginForm = $this->createForm(ShopifyLoginType::class);

        $loginForm->handleRequest($request);
        if ($loginForm->isSubmitted() and $loginForm->isValid()) {
            $formData = $loginForm->getData();
            return $this->redirectToRoute('auth_login', ['shop' => $formData['shop']]);
        }

        return $this->render('auth/login.html.twig', [
            'loginForm' => $loginForm->createView()
        ]);
    }

    #[Route('/auth/login/{shop}', name: 'auth_login')]
    public function auth(string $shop) {
        $oAuthResponse = OAuth::begin($shop, $this->generateUrl('auth_callback'), true);

        return new RedirectResponse($oAuthResponse);
    }

    #[Route('/auth/callback', name: 'auth_callback')]
    public function callback(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        /** @var Shop $shop */
        $shop = $security->getUser();

        if ($shop->getAccessToken() === null) {
            dump("get access token");
            $http = new Http($shop->getUrl());
            $response = $http->post("/admin/oauth/access_token", [
                'client_id'     =>	$this->getParameter('shopify.api.key'),
                'client_secret' =>	$this->getParameter('shopify.api.secret'),
                'code'          =>	$request->query->get('code')
            ]);

            $data = $response->getDecodedBody();

            $shop->setAccessToken($data['access_token']);

            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/auth/logout', name: 'auth_logout')]
    public function logout()
    {
        return $this->redirectToRoute('auth_login_page');
    }
}
