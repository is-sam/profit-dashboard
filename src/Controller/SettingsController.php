<?php

namespace App\Controller;

use App\Entity\CustomCost;
use App\Entity\Variant;
use App\Form\CustomCostType;
use App\Repository\CustomCostRepository;
use App\Repository\ProductRepository;
use App\Service\ShopifyAdminAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        ProductRepository $productRepository,
    ): Response {
        $shop = $this->getUser();

        $products = $productRepository->getProductsWithVariantsByShop($shop);

        return $this->render('settings/cogs.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/settings/cogs/sync-products', name: 'settings_sync_products')]
    public function syncProducts(
        ShopifyAdminAPIService $adminAPI
    ): Response {
        $adminAPI->syncProducts();

        return $this->redirectToRoute('settings_cogs');
    }

    #[Route('/settings/cogs/{id}/update', name: 'settings_cogs_update')]
    public function cogsUpdate(
        Variant $variant,
        Request $request,
    ): Response {
        if ($variant->getProduct()->getShop() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $newCost = floatval($data['cost']);

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
        CustomCostRepository $customCostRepository,
    ): Response {
        $shop = $this->getUser();

        $customCostForm = $this->createForm(CustomCostType::class);
        $customCostForm->handleRequest($request);

        if ($customCostForm->isSubmitted() and $customCostForm->isValid()) {
            $customCostRepository->save($customCostForm->getData(), $shop);
            $this->addFlash('success', 'Custom cost added!');

            return $this->redirectToRoute('settings_custom');
        }

        $costs = $customCostRepository->findBy([
            'shop' => $shop,
        ]);

        return $this->renderForm('settings/custom.html.twig', [
            'form' => $customCostForm,
            'costs' => $costs,
        ]);
    }

    #[Route('/settings/custom-costs/{customCost}/delete', name: 'settings_custom_delete')]
    public function custom_delete(CustomCost $customCost, CustomCostRepository $customCostRepository): Response
    {
        $customCostRepository->remove($customCost);

        return $this->redirectToRoute('settings_custom');
    }
}
