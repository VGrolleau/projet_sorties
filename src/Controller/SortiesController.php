<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Event;
use App\Entity\EventState;
use App\Entity\Location;
use App\Entity\User;
use App\Form\CityType;
use App\Form\CreateEventType;
use App\Form\LocationType;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $user = $this->getUser();
        if ($user) {
            return new RedirectResponse('/sorties/public/sorties_home');
        } else {
            return new RedirectResponse('/sorties/public/login');
        }
    }

    /**
     * @Route("/sorties_home", name="sorties_home")
     */
    public function home(EventRepository $eventRepository, Request $request): Response
    {
        $data = new SeachData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form ->handleRequest($request);
        $event = $eventRepository->findSearch($data);
        return $this->render('sorties/home.html.twig', [
            'events' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sorties/details/{id}", name="sorties_detail")
     */
    public function details(int $id): Response
    {
        return $this->render('sorties/detail.html.twig');
    }

    /**
     * @Route("/sorties/edit/{id}", name="sorties_edit")
     */
    public function edit(int $id): Response
    {
        return $this->render('sorties/edit.html.twig');
    }

    /**
     * @Route("/sorties/create", name="sorties_create")
     */
    public function create(
        Request $request,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();
        $event = new Event();
//        $location = new Location();
        $event->setCreationDate(new \DateTime());
        $event->setOrganizer($user);
        $eventForm = $this->createForm(CreateEventType::class, $event);
        $eventForm->handleRequest($request);
//        $locationForm = $this->createForm(LocationType::class, $location);
        $locationForm = $this->createForm(LocationType::class);
        $cityForm = $this->createForm(CityType::class);
//        $locationForm->handleRequest($request);
        $eventRepo = $eventRepository->findInfosCreate();
        // todo : traiter le formulaire

//        if ($locationForm->isSubmitted()) {
//            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
//            if ($locationForm->isValid()) {
//                $entityManager->persist($location);
////                dd($location);
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

        if ($eventForm->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($eventForm->isValid() ) {
//                if ($event->getStartDate() >= $event->getCreationDate()) {
                    $entityManager->persist($event);
//                dd($event);
                    $entityManager->flush();
                    // do anything else you need here, like send an email

                    $this->addFlash('success', 'Sortie ajoutée !');
                    return $this->redirectToRoute('sorties_home');
//                }
//            } else {
//                //sinon ça bugue dans la session, ça me déconnecte
//                //refresh() permet de re-récupérer les données fraîches depuis la bdd
//                $entityManager->refresh($event);
            }
        }

        return $this->render('sorties/create.html.twig', [
            'eventForm' => $eventForm->createView(),
            'locationForm' => $locationForm->createView(),
            'cityForm' => $cityForm->createView(),
            'eventRepo' => $eventRepo
        ]);
    }

//    /**
//     * @Route("/sorties/cancel/{id}", name="sorties_canceled")
//     */
//    public function cancel(Event $event, EntityManagerInterface $entityManager): Response{
//       $eventState = $this->getDoctrine()
//           ->getRepository(EventState::class)
//           ->findOneBy(['name' => 'Canceled']);
//       $event-> setEventState($eventState);
//        $entityManager->persist($event);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('sorties_home');
//    }
    /**
     * @Route("/sorties/publish/{id}", name="sorties_publish")
     */
    public function publish(Event $event, EntityManagerInterface $entityManager): Response{
        $eventState = $this->getDoctrine()
            ->getRepository(EventState::class)
            ->findOneBy(['name' => 'Created']);
        $event-> setEventState($eventState);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('sorties_home');
    }
    /**
     * @Route("/sorties/register/{id}/{user}", name="sorties_register")
     */
    public function register(Event $event,User $user, EntityManagerInterface $entityManager){
        $event-> addUser($user);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('sorties_home');
    }
    /**
     * @Route("/sorties/unsubscribe/{id}/{user}", name="sorties_unsubscribe")
     */
    public function unsubscribe(Event $event,User $user, EntityManagerInterface $entityManager){
        $event-> removeUser($user);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('sorties_home');
    }
    /**
     * @Route("/sorties/cancel/{id}", name="sorties_canceled")
     */
    public function cancel(int $id)
    {
        return $this->render('sorties/cancel.html.twig');
    }
}
