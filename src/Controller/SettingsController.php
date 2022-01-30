<?php

namespace App\Controller;

use App\Repository\CustomCostRepository;
use App\Repository\ProductRepository;
use App\Repository\VariantRepository;
use App\Service\SettingsService;
use App\Service\ShopifyAdminAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class DefaultController.
 */
class SettingsController extends AbstractController
{
    #[Route('/settings/cogs', name: 'settings_cogs')]
    public function cogs(
        Request $request,
        ProductRepository $productRepository,
        ShopifyAdminAPIService $adminAPI
    ) {
        $shop = $this->getUser();

        if ($request->isMethod('POST')) {
            $adminAPI->syncProducts();
        }

        $products = $productRepository->getProductsWithVariantsByShop($shop);

        return $this->render('settings/cogs.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/settings/cogs/update/{variantId}', name: 'settings_cogs_update')]
    public function cogsUpdate(int $variantId, Request $request, VariantRepository $variantRepository, EntityManagerInterface $entityManager)
    {
        $variant = $variantRepository->find($variantId);

        if (!$variant) {
            throw new Exception("Variant $variantId does not exist !");
        }

        $data = json_decode($request->getContent(), true);

        $newCost = $data['cost'];

        // TODO: validate cost
        $variant->setCost($newCost);
        $entityManager->flush();

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
    ) {
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

    #[Route('/settings/custom-costs/{id}/delete', name: 'settings_custom_delete')]
    public function custom_delete(
        string $id,
        Request $request,
        SettingsService $settingsService,
        CustomCostRepository $customCostRepository,
        Security $security
    ) {
        $customCost = $customCostRepository->find($id);

        if (empty($customCost)) {
            throw new Exception("Custom cost $id does not exist!");
        }

        $settingsService->deleteCustomCost($customCost);

        return $this->redirectToRoute('settings_custom');
    }

    #[Route('/settings/shipping', name: 'settings_shipping')]
    public function shipping(
        Request $request,
        Security $security
    ) {
        $shop = $security->getUser();

        return $this->render('settings/shipping.html.twig', [
            'profiles' => [],
        ]);
    }
}
