<?php

namespace App\Controller;

use App\Form\Type\DashboardSearchType;
use App\Form\Model\DashboardSearch;
use App\Service\DashboardService;
use App\Service\ShopifyAdminAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        DashboardService $dashboardService
    ) {
        $dashboardSearch = new DashboardSearch();

        $searchForm = $this->createForm(DashboardSearchType::class, $dashboardSearch);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() and $searchForm->isValid()) {
            $dashboardSearch = $searchForm->getData();
        }

        // REST call: get orders by date range
        $orders = $adminAPI->getOrders($dashboardSearch->getDateStart(), $dashboardSearch->getDateEnd());

        // calculate dashboard data
        $dashboard = $dashboardService->calculateData($orders);

        return $this->render('home.html.twig', [
            'searchForm'    => $searchForm->createView(),
            'dashboard'     => $dashboard,
            'dateStart'     => $dashboardSearch->getDateStart(),
            'dateEnd'       => $dashboardSearch->getDateEnd(),
            'orders'        => $orders
        ]);
    }
}
