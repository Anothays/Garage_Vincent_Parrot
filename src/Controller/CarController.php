<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use App\Repository\CarRepository;
use App\Repository\ContactMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cars')]
class CarController extends AbstractController
{

    #[Route('/', name: 'app_cars_index', methods: ['GET', 'POST'])]
    public function index(
        CarRepository $carsRepository,
        Request $request,
        ContactMessageRepository $contactMessageRepository): Response
    {
        // Create form
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        // Get min and max values in database for mileage, price, registrationYear
        $MinMaxValues = $carsRepository->getMinMaxValues();
        $params = [
            'minMileage' => $request->get('mileage-min') ?? $MinMaxValues['minMileage'],
            'maxMileage' => $request->get('mileage-max') ?? $MinMaxValues['maxMileage'],
            'minPrice' => $request->get('price-min') ?? $MinMaxValues['minPrice'],
            'maxPrice' => $request->get('price-max') ?? $MinMaxValues['maxPrice'],
            'minYear' => $request->get('year-min') ? new \DateTime("{$request->get('year-min')}-01-01") : $MinMaxValues['minYear'],
            'maxYear' => $request->get('year-max') ? new \DateTime("{$request->get('year-max')}-12-31") : $MinMaxValues['maxYear'],
            'selectPagination' => $request->get('selectPagination') % 5 === 0 && $request->get('selectPagination') !== null ? $request->get('selectPagination') : "5",
            'page' => $request->get('page') ?? "1"
        ];

        // handle ajax filters
        if ($request->headers->get('X-Requested-With') === "XMLHttpRequest" && $request->get('ajax') === '1') {

            // Handle submit form with ajax
            if ($form->isSubmitted() && $form->isValid()) {
                $contactMessageRepository->saveAndUpdateAssociatedCar($contactMessage, $carsRepository);
                return $this->json([
                    'message' => 'Nous avons bien reçus votre message, nous reviendrons vers vous aussi vite que possible'
                ]);
            }
            return $this->handleAjaxFilters($params, $carsRepository, $MinMaxValues);
        }

        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findByFilters($params),
            'queryParams' => $params,
            'minMaxValues' => $MinMaxValues,
            'form' => $form
        ]);
    }

    public function handleAjaxFilters($params, CarRepository $carsRepository, $MinMaxValues) : JsonResponse
    {
        $cars = $carsRepository->findByFilters($params);
        return $this->json([
            'content' => $this->render('cars/cars_list_items.html.twig', ['cars' => $cars]),
            'contentCount' => $cars['count'],
            'pagination' => $this->render('cars/pagination_links.html.twig', ['cars' => $cars]),
            'queryParams' => $params,
        ]);

    }

    #[Route('/{id}', name: 'app_cars_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Car $car, ContactMessageRepository $contactRepository): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage->setCreatedBy($this->getUser() ? $this->getUser()->getFullname() : 'visiteur anonyme');
            $contactRepository->saveAndUpdateAssociatedCar($contactMessage, $car);
            return $this->json([
                'message' => 'Nous avons bien reçus votre message, nous reviendrons vers vous aussi vite que possible'
            ]);
        }

        return $this->render('cars/show.html.twig', [
            'car' => $car,
            'form' => $form
        ]);
    }
}
