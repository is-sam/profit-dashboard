<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShopifySession;
use App\Entity\Variant;
use App\Exception\ShopifySessionDataEmptyException;
use App\Form\Type\DashboardSearchType;
use App\Form\Model\DashboardSearch;
use App\Repository\VariantRepository;
use App\Service\DashboardService;
use App\Service\FacebookAPIService;
use App\Service\ShopifyAdminAPIService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Shopify\Clients\Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request,
        ShopifyAdminAPIService $adminAPI,
        FacebookAPIService $facebookAPI,
        DashboardService $dashboardService,
        VariantRepository $variantRepository
    ) {
        $shop = $request->query->get('shop');
        if (empty($shop)) {
            dd($request->query);
        }
        $adminAPI->setShop($shop);

        $dashboardSearch = new DashboardSearch();
        // $dashboardSearch->setDateStart(new DateTime("12/12/2021"));
        // $dashboardSearch->setDateEnd(new DateTime("12/15/2021"));

        $searchForm = $this->createForm(DashboardSearchType::class, $dashboardSearch);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() and $searchForm->isValid()) {
            $dashboardSearch = $searchForm->getData();
        }

        // REST call: get orders by date range
        try {
            $orders = $adminAPI->getOrders($dashboardSearch->getDateStart(), $dashboardSearch->getDateEnd());
        } catch (ShopifySessionDataEmptyException $e) {
            if ($request->query->get('authenticated') == 1) {
                throw new ShopifySessionDataEmptyException();
            }
            return $this->redirectToRoute('auth_login', ['shop' => $shop]);
        }

        $variants = $variantRepository->getVariantsByShop($shop);

        // REST call: get Facebook Ad spend
        $fbAdSpend = $facebookAPI->getAdSpendByDate($dashboardSearch->getDateStart(), $dashboardSearch->getDateEnd());

        // calculate dashboard data
        $dashboard = $dashboardService->calculateData($orders, $variants, $fbAdSpend);

        return $this->render('home.html.twig', [
            'shop'          => $shop,
            'searchForm'    => $searchForm->createView(),
            'dashboard'     => $dashboard,
            'dateStart'     => $dashboardSearch->getDateStart(),
            'dateEnd'       => $dashboardSearch->getDateEnd()
        ]);
    }
}
