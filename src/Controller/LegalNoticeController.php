<?php

namespace App\Controller;

use App\Repository\GarageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends AbstractController
{
    #[Route('/legal-notice', name: 'app_legal_notice')]
    public function index(GarageRepository $garageRepository): Response
    {
        return $this->render('legal_notice/index.html.twig', [
            'garage' => $garageRepository->findAll()[0]
        ]);
    }
    #[Route('/privacy-policy', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('legal_notice/privacy_policy.html.twig', [

        ]);
    }
}
