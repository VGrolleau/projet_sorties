<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UpdateProfilType;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UpdateProfilController extends AbstractController
{
    /**
     * @Route("/updateprofil", name="update_profil")
     */
    public function updateProfil(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserAuthenticatorInterface $authenticator,
        AppAuthenticator $formAuthenticator
    ): Response
    {
        //$user = $this->getUser();
        $userTemp = new User();
        $form = $this->createForm(UpdateProfilType::class, $userTemp);

        $form->handleRequest($request);
        /*  if(!($user->getUserIdentifier() === $userTemp->getUserIdentifier())){
              $user->setUsername($userTemp->getUserIdentifier());
          }
        /*if(!($user->getFirstname() === $userTemp->getFirstname())){
              $user->setFirstname($userTemp->getFirstname());
          }
          if(!($user->getlastname() === $userTemp->getlastname())){
              $user->setlastname($userTemp->getlastname());
          }
          if(!($user->getPhone() === $userTemp->getPhone())){
              $user->setPhone($userTemp->getPhone());
          }
          if(!($user->getEmail() === $userTemp->getEmail())){
              $user->setEmail($userTemp->getEmail());
          }
          if(!($user->getCampus() === $userTemp->getCampus())){
              $user->setCampus($userTemp->getCampus());
          }*/
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $userTemp->setPassword(
                $passwordEncoder->encodePassword(
                    $userTemp,
                    $form->get('Password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userTemp);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $authenticator->authenticateUser($userTemp, $formAuthenticator, $request);
        }

        return $this->render('updateprofil/upProfil.html.twig', [
            'UpdateProfil' => $form->createView(),
        ]);
    }
}
