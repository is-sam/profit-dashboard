<?php

namespace App\Controller;

use App\Entity\CustomCost;
use App\Entity\ShippingProfile;
use App\Entity\Variant;
use App\Repository\CustomCostRepository;
use App\Repository\ProductRepository;
use App\Repository\ShippingProfileRepository;
use App\Repository\VariantRepository;
use App\Service\SettingsService;
use App\Service\ShopifyAdminAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class DefaultController.
 */
class SettingsController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/settings/cogs', name: 'settings_cogs')]
    public function cogs(
        Request $request,
        ProductRepository $productRepository,
        ShopifyAdminAPIService $adminAPI
    ): Response {
        $shop = $this->getUser();

        if ($request->isMethod('POST')) {
            $adminAPI->syncProducts();
        }

        $products = $productRepository->getProductsWithVariantsByShop($shop);

        return $this->render('settings/cogs.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/settings/cogs/{variant}/update', name: 'settings_cogs_update')]
    public function cogsUpdate(
        Variant $variant,
        Request $request,
    ): Response {
        $data = json_decode($request->getContent(), true);

        $newCost = $data['cost'];

        // TODO: validate cost
        $variant->setCost($newCost);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Cost updated',
        ]);
    }

    #[Route('/settings/custom-costs', name: 'settings_custom')]
    public function custom(
        Request $request,
        SettingsService $settingsService,
        CustomCostRepository $customCostRepository,
        Security $security
    ): Response {
        if ($request->isMethod('POST')) {
            $settingsService->saveCustomCost($request->request->all());
        }

        $shop = $security->getUser();
        $costs = $customCostRepository->findBy([
            'shop' => $shop,
        ]);

        return $this->render('settings/custom.html.twig', [
            'costs' => $costs,
        ]);
    }

    #[Route('/settings/custom-costs/{customCost}/delete', name: 'settings_custom_delete')]
    public function custom_delete(CustomCost $customCost): Response
    {
        $this->entityManager->remove($customCost);
        $this->entityManager->flush();

        return $this->redirectToRoute('settings_custom');
    }

    #[Route('/settings/shipping', name: 'settings_shipping')]
    public function shipping(
        Request $request,
        Security $security,
        SettingsService $settingsService,
        ShippingProfileRepository $shippingProfileRepository,
        VariantRepository $variantRepository,
    ): Response {
        $shop = $security->getUser();

        if ($request->isMethod('POST')) {
            $settingsService->saveShippingProfile($request->request->all());
        }

        $variants = $variantRepository->getVariantsByShop($shop);

        $profiles = $shippingProfileRepository->findBy([
            'shop' => $shop,
            'isVariantProfile' => false
        ]);

        $variantProfiles = $shippingProfileRepository->findBy([
            'shop' => $shop,
            'isVariantProfile' => true
        ]);

        return $this->render('settings/shipping.html.twig', [
            'variants' => $variants,
            'profiles' => $profiles,
            'variantProfiles' => $variantProfiles,
        ]);
    }

    #[Route('/settings/shipping/{profile}/delete', name: 'settings_shipping_delete')]
    public function shippinh_delete(ShippingProfile $profile): Response
    {
        $this->entityManager->remove($profile);
        $this->entityManager->flush();

        return $this->redirectToRoute('settings_shipping');
    }
}
