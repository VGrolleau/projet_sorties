<?php

namespace App\Controller;


use App\Form\SearchCityType;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/city", name="admin_city")
     */
    public function listVilles(
        Request $request,
        CityRepository $cityRepository
    ): Response
    {

        $cities = $cityRepository->findAllCities();

        $searchCityForm = $this->createForm(SearchCityType::class);

        $searchCityForm->handleRequest($request);

        if ($searchCityForm->isSubmitted() && $searchCityForm->isValid()) {
            $search = $searchCityForm->getData();
            $cities = $cityRepository->findByName($search['search']);
        }

        if (!$cities) {
            throw $this->createNotFoundException('Aucune ville n\'existe avec ce nom !');
        }


            return $this->render('admin/city.html.twig', [
                'cities' => $cities,
                'searchCityForm' => $searchCityForm->createView()
            ]);
        }

}
