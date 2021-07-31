<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Event;
use App\Form\CityType;
use App\Form\CreateEventType;
use App\Form\LocationType;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
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
        return new RedirectResponse('/sorties/public/login');
    }

    /**
     * @Route("/sorties_home", name="sorties_home")
     */
    public function home(EventRepository $eventRepository, Request $request): Response
    {
        $data = new SeachData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form ->handleRequest($request);
        $event = $eventRepository->findSearch($data);
        return $this->render('sorties/home.html.twig', [
            'events' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sorties/details/{id}", name="sorties_detail")
     */
    public function details(int $id): Response
    {
        return $this->render('sorties/detail.html.twig');
    }

    /**
     * @Route("/sorties/create", name="sorties_create")
     */
    public function create(EventRepository $eventRepository): Response
    {
        $event = new Event();
        $eventRepo = $eventRepository->findInfosCreate();
        $eventForm = $this->createForm(CreateEventType::class, $event);
        $locationForm = $this->createForm(LocationType::class);
        $cityForm = $this->createForm(CityType::class);

        // todo : traiter le formulaire

        return $this->render('sorties/create.html.twig', [
            'eventForm' => $eventForm->createView(),
            'locationForm' => $locationForm->createView(),
            'cityForm' => $cityForm->createView(),
            'eventRepo' => $eventRepo
        ]);
    }
}
