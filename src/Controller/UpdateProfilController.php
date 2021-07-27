<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UpdateProfilController extends AbstractController
{
    /**
     * @Route("/updateprofil", name="update_profil")
     */
    public function updateProfil(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfilType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                $this->addFlash('success', 'Profil modifié !');
                return $this->redirectToRoute('update_profil');
            } else {
                //sinon ça bugue dans la session, ça me déconnecte
                //refresh() permet de re-récupérer les données fraîches depuis la bdd
                $entityManager->refresh($user);
            }
        }
        return $this->render('updateprofil/upProfil.html.twig', [
        'UpdateProfil' => $form->createView(),
    ]);
    }

            /**
             * Modification du profil
             *
             * @Route("/updateprofil/mot-de-passe", name="user_update_password")
             */
            public function editPassword(
                Request $request,
                EntityManagerInterface $entityManager,
                UserPasswordEncoderInterface $passwordEncoder
            ): Response
            {
                //récupère le user en session
                //ne jamais récupérer le user en fonction de l'id dans l'URL !
                /** @var User $user */
                $user = $this->getUser();

                $form = $this->createForm(EditPasswordType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $hash = $passwordEncoder->encodePassword($user, $form->get('new_password')->getData());
                    $user->setPassword($hash);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Mot de passe modifié !');
                    //sinon ça bugue dans la session, ça me déconnecte
                    //refresh() permet de re-récupérer les données fraîches depuis la bdd
                    $entityManager->refresh($user);

                    return $this->redirectToRoute("user_profile", ["id" => $user->getId()]);
                }

                return $this->render('user/edit_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    }

