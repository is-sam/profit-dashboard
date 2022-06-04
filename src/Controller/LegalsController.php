<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalsController extends AbstractController
{
    #[Route('/legals/privacy-policy', name: 'legals_privacy_policy')]
    public function index(): Response
    {
        return $this->render('legals/privacy-policy.html.twig', []);
    }
}
