<?php

namespace App\Controller;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/services')]
class ServiceController extends AbstractController
{
    #[Route('/{id}')]
    public function show(Service $service): Response
    {


        return $this->render('services/service_show.html.twig', [
            'service' => $service
        ]);
    }
}