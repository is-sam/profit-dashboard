<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ShopifyAdminAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'settings_home')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        ShopifyAdminAPIService $adminAPI
    ) {
        $shop = $this->getUser();

        if ($request->isMethod('POST')) {
            $adminAPI->syncProducts();
        }

        $products = $productRepository->getProductsWithVariantsByShop($shop);

        return $this->render('settings/home.html.twig', [
            'products'  => $products
        ]);
    }
}
