<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
//    /**
//     * @Route("/api/location/create", name="location_create")
//     */
//    public function create(
//        LocationRepository $locationRepository,
//        Request $request,
//        EntityManagerInterface $entityManager
//    ): Response
//    {
//        $location = new Location();
//        $form = $this->createForm(Location::class, $location);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
//            if ($form->isValid()) {
//
//                $entityManager->persist($location);
//                $entityManager->flush();
//                // do anything else you need here, like send an email
//
//                $this->addFlash('success', 'Lieu ajouté !');
////                return $this->redirectToRoute('sorties_create');
//            } else {
//                //sinon ça bugue dans la session, ça me déconnecte
//                //refresh() permet de re-récupérer les données fraîches depuis la bdd
//                $entityManager->refresh($location);
//            }
//        }
//
//        return $this->render('location/location.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }

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
