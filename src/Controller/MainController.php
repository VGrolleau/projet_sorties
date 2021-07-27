<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home_redirect")
     */
    public function home_redirect() {
        return new RedirectResponse('/~wahac/sorties/public/login');
    }

    /**
     * @Route("/main_home", name="main_home")
     */
    public function home() {
        return $this->render('main/home.html.twig');
    }
}