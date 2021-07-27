<?php

namespace App\Controller;

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
     * @Route("/", name="sorties_home")
     */
    public function home(): Response
    {


        return $this->render('sorties/home.html.twig', [

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
