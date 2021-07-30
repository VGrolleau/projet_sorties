<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/api/location/create", name="location_create")
     */
    public function create(): Response
    {


        return $this->render('location/location.html.twig', [

        ]);
    }

//    /**
//     * @Route("/location", name="location")
//     */
//    public function location(): Response
//    {
//        return $this->render('location/location.html.twig', [
//            'controller_name' => 'LocationController',
//        ]);
//    }
}
