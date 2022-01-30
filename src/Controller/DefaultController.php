<?php

namespace App\Controller;

use App\Form\Model\DashboardSearch;
use App\Form\Type\DashboardSearchType;
use App\Service\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        DashboardService $dashboardService,
    ) {
        $dashboardSearch = new DashboardSearch();
        $searchForm = $this->createForm(DashboardSearchType::class, $dashboardSearch);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() and $searchForm->isValid()) {
            $dashboardSearch = $searchForm->getData();
        }

        $data = $dashboardService->getData($dashboardSearch->getDateStart(), $dashboardSearch->getDateEnd());

        return $this->render('home.html.twig', [
            'searchForm' => $searchForm->createView(),
            'data' => $data,
            'dateStart' => $dashboardSearch->getDateStart(),
            'dateEnd' => $dashboardSearch->getDateEnd(),
        ]);
    }
}
