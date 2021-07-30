<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\CollectionCity;
use App\Form\CityType;
use App\Form\CollectionCityType;
use App\Form\SearchCityType;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
        $searchCityForm = $this->createForm(SearchCityType::class);
        $cities= $cityRepository->findAll();
        $citys = new ArrayCollection();

        foreach ($cities->getCities() as $city) {
            $citys->add($city);
        }

        $cityForm = $this->createForm(CollectionCityType::class, $cities);

        $searchCityForm->handleRequest($request);


        if ($searchCityForm->isSubmitted() && $searchCityForm->isValid()){
            $search = $searchCityForm->getData();
            $cities = $cityRepository->findByName($search['search']);
        }

        if (!$cities){
            throw $this->createNotFoundException('Aucune ville n\'existe avec ce nom !');
        }

        return $this->render('admin/city.html.twig', [
            'cities' => $cities,
            'searchCityForm' => $searchCityForm->createView(),
            'cityForm' => $cityForm->createView(),
        ]);
    }
}
