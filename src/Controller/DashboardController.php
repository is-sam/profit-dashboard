<?php

namespace App\Controller;

use App\Form\Model\DashboardSearch;
use App\Form\Type\DashboardSearchType;
use App\Service\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController.
 */
class DashboardController extends AbstractController
{
    private const DASHBOARD_SEARCH_SESSION_KEY = 'dashboard_search';

    #[Route('/', name: 'home')]
    public function home(
        Request $request,
        DashboardService $dashboardService,
        SessionInterface $session
    ) {
        $dashboardSearch = $session->get(self::DASHBOARD_SEARCH_SESSION_KEY, new DashboardSearch());
        $searchForm = $this->createForm(DashboardSearchType::class, $dashboardSearch);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() and $searchForm->isValid()) {
            $dashboardSearch = $searchForm->getData();
            $session->set(self::DASHBOARD_SEARCH_SESSION_KEY, $dashboardSearch);

            return $this->redirect($this->generateUrl('home'));
        }

        $data = $dashboardService->getData($dashboardSearch->getDateStart(), $dashboardSearch->getDateEnd());

        return $this->renderForm('dashboard.html.twig', [
            'form' => $searchForm,
            'data' => $data,
            'dateStart' => $dashboardSearch->getDateStart(),
            'dateEnd' => $dashboardSearch->getDateEnd(),
        ]);
    }
}
