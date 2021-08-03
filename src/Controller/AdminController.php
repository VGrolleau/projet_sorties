<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreationUserAdminType;
use App\Form\RegistrationFormType;
use App\Form\SearchCityType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_nav")
     */
    public function redirectNav(): Response
    {
        return $this->render('admin/NavAdmin.html.twig');
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function listUsers(
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        $users = $userRepository->findAllUser();
        $searchUserForm = $this->createForm(SearchCityType::class);

        $searchUserForm->handleRequest($request);

        if ($searchUserForm->isSubmitted() && $searchUserForm->isValid()) {
            $search = $searchUserForm->getData();
            $users = $userRepository->findByName($search['search']);
        }

        if (!$users) {
            throw $this->createNotFoundException('Aucun utilisateur n\'existe avec ce nom ou ce prénom !');
        }

        return $this->render('admin/user/users.html.twig',[
            'users' => $users,
            'form' => $searchUserForm->createView()
        ]);
    }

    /**
     * @Route("/admin/users/disabled/{id}", name="admin_users_disabled")
     */
    public function disabledUsers(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $users = $userRepository->find($id);

        $users->setIsActive(false);

        $entityManager->persist($users);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur désactivé !');
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/users/activate/{id}", name="admin_users_activate")
     */
    public function activateUsers(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $users = $userRepository->find($id);

        $users->setIsActive(true);

        $entityManager->persist($users);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur Activé !');
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/users/create", name="admin_users_create")
     */
    public function createUsers(
        Request $request,
        SluggerInterface $slugger,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $user->setCreatedDate(new \DateTime('now'));
        $user->setIsAdmin(false);
        $user->setIsActive(true);

        $form = $this->createForm(CreationUserAdminType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

//            PICTURE
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

            $isAdmin = $form->get('isAdmin')->getData();
            if ($isAdmin){
                $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/user/createUser.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     */
    public function deleteUsers(
        User $user,
        EntityManagerInterface $entityManager
    ): Response
    {
        try{
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé !');
         } catch (\Exception $e) {
            $this->addFlash("danger", "Vous ne pouvez pas supprimer cet utilisateur car il organise une sortie !");
        }
        return $this->redirectToRoute('admin_users');
    }
}
