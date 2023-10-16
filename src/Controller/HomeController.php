<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Entity\TestimonialCreatedByUser;
use App\Form\TestimonialType;
use App\Repository\ScheduleRepository;
use App\Repository\ServiceRepository;
use App\Repository\TestimonialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TestimonialRepository $testimonialRepository, ServiceRepository $servicesRepository): Response
    {
        $testimonial = new Testimonial();
        $form = $this->createForm(TestimonialType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testimonial->setCreatedBy($this->getUser() ? $this->getUser()->getFullname() : 'Visiteur anonyme');
            $testimonialRepository->save($testimonial, true);
            return $this->json([
                'message' => 'Merci pour votre avis !'
            ]);
        }

        if ($request->get('ajax') === "1") {
            $page = $request->get('page');
            return $this->json($testimonialRepository->findTestimonialsPaginated($page), 200, [], ['groups' => 'testimonial']);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'testimonials' => $testimonialRepository->findBy(["approved" => "1"],["createdAt" => "DESC"], 5, 0),
            'services' => $servicesRepository->findBy(['published' => 1]),
        ]);
    }
}
