<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\Event;
use App\Entity\EventState;
use App\Entity\User;
use App\Form\CityType;
use App\Form\CreateEventType;
use App\Form\EventMotifCancelledType;
use App\Form\LocationType;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Count;

class SortiesController extends AbstractController
{
    /**
     * @Route("/", name="home_redirect")
     */
    public function home_redirect() {
        $user = $this->getUser();
        if ($user) {
            return new RedirectResponse('/~wahac/sorties/public/sorties_home');
        } else {
            return new RedirectResponse('/sorties/public/login');
        }
    }

    /**
     * @Route("/sorties_home", name="sorties_home")
     */
    public function home(EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $date = new \DateTime('now',new \DateTimeZone('europe/paris'));
        $data = new SeachData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form ->handleRequest($request);
        $events = $eventRepository->findSearch($data, $user);
        foreach($events as $event) {
            $eventState = $event->getEventState();
            if ($eventState != 'Annulé' || $eventState != 'Créé'){
                $dateRegLimite = $event->getRegistrationLimitDate();
                $dateDebutEvent = $event->getStartDate();
                $nbRegistre = \count($event->getUsers());
                $nbMaxRegistre = $event->getMaxRegistrations();
                $duration = $event->getDuration();
                $dateFinEvent = clone $dateDebutEvent;
                $dateFinEvent->add(new DateInterval('PT' . $duration . 'M'));
                if ($dateRegLimite <= $date) {
                    $eventState = $this->getDoctrine()
                        ->getRepository(EventState::class)
                        ->findOneBy(['name' => 'Fermé']);
                    $event->setEventState($eventState);
                }
                if ($dateDebutEvent <= $date and $dateFinEvent > $date) {
                    $eventState = $this->getDoctrine()
                        ->getRepository(EventState::class)
                        ->findOneBy(['name' => 'En cours']);
                    $event->setEventState($eventState);
                    //                $entityManager->persist($event);
                    //                $entityManager->flush();
                }
                if ($dateFinEvent < $date) {
                    $eventState = $this->getDoctrine()
                        ->getRepository(EventState::class)
                        ->findOneBy(['name' => 'Terminé']);
                    $event->setEventState($eventState);
                    //                $entityManager->persist($event);
                    //                $entityManager->flush();
                }
                if($nbRegistre == $nbMaxRegistre){
                    $eventState = $this->getDoctrine()
                        ->getRepository(EventState::class)
                        ->findOneBy(['name' => 'Fermé']);
                    $event->setEventState($eventState);
                }

            }
        }
        return $this->render('sorties/home.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sorties/details/{id}", name="sorties_detail")
     */
    public function details(
        int $id,
        EventRepository $eventRepository
    ): Response
    {
        $event = $eventRepository->find($id);

        return $this->render('sorties/detail.html.twig', [
            'event' => $event
        ]);
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
        $btName = $request->get( 'button' );
        $user = $this->getUser();
        $event = new Event();
        $event->setCreationDate(new \DateTime());
        $event->setOrganizer($user);
        $eventForm = $this->createForm(CreateEventType::class, $event);
        $eventForm->handleRequest($request);
        $locationForm = $this->createForm(LocationType::class);
        $cityForm = $this->createForm(CityType::class);
        $eventRepo = $eventRepository->findInfosCreate();

        if ($eventForm->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($eventForm->get('publish')->isClicked() && $eventForm->isValid() ) {
                $eventState = $this->getDoctrine()
                    ->getRepository(EventState::class)
                    ->findOneBy(['name' => 'Ouvert']);
                $event-> setEventState($eventState);
                $entityManager->persist($event);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Sortie publiée !');
                return $this->redirectToRoute('sorties_home');
            } else {
                $this->addFlash('danger', 'Sortie non publiée !');
            }

            if ($eventForm->get('registerEvent')->isClicked() && $eventForm->isValid() ) {
                $eventState = $this->getDoctrine()
                    ->getRepository(EventState::class)
                    ->findOneBy(['name' => 'Créé']);
                $event-> setEventState($eventState);
                $entityManager->persist($event);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Sortie enregistrée !');
                return $this->redirectToRoute('sorties_home');
            } else {
                $this->addFlash('danger', 'Sortie non enregistrée !');
            }
        }

        return $this->render('sorties/create.html.twig', [
            'eventForm' => $eventForm->createView(),
            'locationForm' => $locationForm->createView(),
            'cityForm' => $cityForm->createView(),
            'eventRepo' => $eventRepo
        ]);
    }

    /**
     * @Route("/sorties/publish/{id}", name="sorties_publish")
     */
    public function publish(Event $event, EntityManagerInterface $entityManager): Response{
        $eventState = $this->getDoctrine()
            ->getRepository(EventState::class)
            ->findOneBy(['name' => 'Ouvert']);
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
    public function cancel(
        int $id,
        EventRepository $eventRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ):Response
    {
        $event = $eventRepository->find($id);
        $form = $this->createForm(EventMotifCancelledType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $motif = $form->get('infos')->getData();
                $event->setInfos($motif);
                $eventState = $this->getDoctrine()
                    ->getRepository(EventState::class)
                    ->findOneBy(['name' => 'Annulé']);
                $event-> setEventState($eventState);
                $entityManager->persist($event);
                $entityManager->flush();

                return $this->redirectToRoute('sorties_home');
            }
        }
        return $this->render('sorties/cancel.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
