<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\EditCampusType;
use App\Form\SearchCityType;
use App\Form\SearchType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCampusController extends AbstractController
{


    /**
     * @Route("/admin/campus", name="admin_campus")
     */
    public function listCampus(
        Request $request,
        CampusRepository $campusRepository
    ): Response
    {
        $campus = $campusRepository->findAllCampus();

        $searchCampusForm = $this->createForm(SearchCityType::class);

        $searchCampusForm->handleRequest($request);

        if ($searchCampusForm->isSubmitted() && $searchCampusForm->isValid()) {
            $search = $searchCampusForm->getData();
            $campus = $campusRepository->findByName($search['search']);
        }

        if (!$campus) {
            throw $this->createNotFoundException('Aucun campus n\'existe avec ce nom !');
        }


        return $this->render('admin/campus/campus.html.twig', [
            'campus' => $campus,
            'searchCampusForm' => $searchCampusForm->createView()
        ]);
    }

    /**
     * @Route("/admin/campus/create", name="admin-campus-create")
     */
    public function create(
        CampusRepository $campusRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $campus = new Campus();

        $form = $this->createForm(EditCampusType::class, $campus);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $entityManager->persist($campus);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Campus ajouté !');
                return $this->redirectToRoute('admin_campus');
            } else {
                //sinon ça bugue dans la session, ça me déconnecte
                //refresh() permet de re-récupérer les données fraîches depuis la bdd
                $this->addFlash('danger', 'Campus non ajouté !');
                $entityManager->refresh($campus);
            }
        }

        return $this->render('admin/campus/createcampus.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/campus/edit/{id}", name="admin-campus-edit")
     */
    public function edit(
        int $id,
        CampusRepository $campusRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $campus = $campusRepository->find($id);
        if (!$campus){
            throw $this->createNotFoundException('oops! This campus does not exists!');
        }
        $form = $this->createForm(EditCampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $entityManager->persist($campus);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Campus modifié !');
                return $this->redirectToRoute('admin_campus');
            } else {
                //sinon ça bugue dans la session, ça me déconnecte
                //refresh() permet de re-récupérer les données fraîches depuis la bdd
                $this->addFlash('danger', 'Campus non modifié !');
                $entityManager->refresh($campus);
            }
        }

        return $this->render('admin/campus/editcampus.html.twig', [
            "campus"=>$campus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/campus/delete/{id}", name="admin-campus-delete")
     */
    public function delete(
        Campus $campus,
        EntityManagerInterface $entityManager
    ){  //Permet aussi de récupérer aussi l'id de l'URL
        $entityManager->remove($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Campus supprimé !');
        return $this->redirectToRoute('admin_campus');
    }


}
