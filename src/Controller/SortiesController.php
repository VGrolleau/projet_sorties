<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CityType;
use App\Form\CreateEventType;
use App\Form\LocationType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/", name="home_redirect")
     */
    public function home_redirect() {
        return new RedirectResponse('/~wahac/sorties/public/login');
    }

    /**
     * @Route("/sorties_home", name="sorties_home")
     */
    public function home(EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findSearch();

        return $this->render('sorties/home.html.twig', [
            'events' => $event,
        ]);
    }

    /**
     * @Route("/sorties/details{id}", name="sorties_detail")
     */
    public function details(int $id): Response
    {
        return $this->render('sorties/detail.html.twig');
    }

    /**
     * @Route("/sorties/create", name="sorties_create")
     */
    public function create(): Response
    {
        $event = new Event();
        $eventForm = $this->createForm(CreateEventType::class, $event);
        $locationForm = $this->createForm(LocationType::class);
        $cityForm = $this->createForm(CityType::class);

        // todo : traiter le formulaire

        return $this->render('sorties/create.html.twig', [
            'eventForm' => $eventForm->createView(),
            'locationForm' => $locationForm->createView(),
            'cityForm' => $cityForm->createView()
        ]);
    }
}
