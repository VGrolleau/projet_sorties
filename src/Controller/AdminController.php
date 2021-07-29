<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/city", name="admin_city")
     */
    public function listVilles(CityRepository $cityRepository): Response
    {


        $city = $cityRepository->findByName();

        return $this->render('admin/city.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
