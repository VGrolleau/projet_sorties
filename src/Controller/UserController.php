<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Form\UpdateProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class UserController extends AbstractController
{
    /**
     *
     * @Route("/user/updateprofil", name="update_profil")
     */
    public function updateProfil(
        Request $request,
        SluggerInterface $slugger
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfilType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            //séparé en 2 if pour pouvoir faire le refresh si le form n'est pas valide
            if ($form->isValid()) {

                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile){
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $pictureFile->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $user->setPictureFileName($newFilename);
                }

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
        return $this->render('user/upProfil.html.twig', [
            "user"=>$user,
            'UpdateProfil' => $form->createView(),
         ]);
    }

    /**
     * Modification du profil
     *
     * @Route("/user/mot-de-passe", name="update_password")
     */
    public function updatePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        //récupère le user en session
        //ne jamais récupérer le user en fonction de l'id dans l'URL !
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class);
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

            return $this->redirectToRoute('update_password');
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/detail/{id}", name="user_details")
     */
    public function details(
        int $id,
        UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->find($id);
        $userConnecte = $this->getUser();
        if (!$user){
            throw $this->createNotFoundException('oops! This user does not exists!');
        }

        return $this->render('user/viewprofil.html.twig', [
            "user"=>$user,
            "userConnecte" => $userConnecte
        ]);
    }

}

