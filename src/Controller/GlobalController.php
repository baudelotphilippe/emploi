<?php

namespace App\Controller;

use App\Services\FranceTravailApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GlobalController extends AbstractController
{
    #[Route('/', name: 'app_global')]
    public function index(FranceTravailApiService $france_travail_api_service): Response
    {
        $offres = $france_travail_api_service->searchJobs(['departement'=>86]);
        // dump($offres);
        return $this->render("base.html.twig",[
            'offres' => $offres,
        ]);
    }
}
