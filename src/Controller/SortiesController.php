<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Form\SearchForm;
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
        return new RedirectResponse('/sorties/public/login');
    }
    /**
     * @Route("/sorties_home", name="sorties_home")
     */
    public function home(EventRepository $eventRepository): Response
    {
        $data = new SeachData();
        $form = $this->createForm(SearchForm::class);
        $event = $eventRepository->findSearch();
        return $this->render('sorties/home.html.twig', [
            'events' => $event,
            'form' => $form->createView()
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
        return $this->render('sorties/create.html.twig');
    }
}
