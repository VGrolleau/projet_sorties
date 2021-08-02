<?php

namespace App\Controller;


use App\Entity\City;
use App\Form\EditCityType;
use App\Form\SearchCityType;
use App\Form\SearchType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCityController extends AbstractController
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


        return $this->render('admin/city/city.html.twig', [
            'cities' => $cities,
            'searchCityForm' => $searchCityForm->createView()
        ]);
    }

    /**
     * @Route("/admin/city/create", name="admin-city-create")
     */
    public function create(
        CityRepository $cityRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $city = new City();

        $form = $this->createForm(EditCityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $entityManager->persist($city);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Ville ajoutée !');
                return $this->redirectToRoute('admin_city');
            } else {
                //sinon ça bugue dans la session, ça me déconnecte
                //refresh() permet de re-récupérer les données fraîches depuis la bdd
                $entityManager->refresh($city);
            }
        }

        return $this->render('admin/city/createcity.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/city/edit/{id}", name="admin-city-edit")
     */
    public function edit(int $id, CityRepository $cityRepository, Request $request): Response
    {
        $city = $cityRepository->find($id);
        if (!$city){
            throw $this->createNotFoundException('oops! This city does not exists!');
        }
        $form = $this->createForm(EditCityType::class, $city);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $entityManager->persist($city);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Ville modifié !');
                return $this->redirectToRoute('admin_city');
            } else {
                //sinon ça bugue dans la session, ça me déconnecte
                //refresh() permet de re-récupérer les données fraîches depuis la bdd
                $entityManager->refresh($city);
            }
        }

        return $this->render('admin/city/editcity.html.twig', [
            "city"=>$city,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/city/delete/{id}", name="admin-city-delete")
     */
    public function delete(City $city, EntityManagerInterface $entityManager){  //Permet aussi de récupérer aussi l'id de l'URL
        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute('admin_city');
    }
}
