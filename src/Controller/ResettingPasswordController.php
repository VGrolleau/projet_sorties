<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/reset-password")
 */
class ResettingPasswordController extends AbstractController
{

    /**
     * @Route("/requete", name="request_resetting")
     */
    public function request(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->loadUserByIdentifier($form->getData()['email']);

            // aucun email associé à ce compte.
            if (!$user) {
                $request->getSession()->getFlashBag()->add('warning', "Cet email n'existe pas.");
                return $this->redirectToRoute('request_resetting');
            }
            return $this->redirectToRoute('resetting', ['id' => $user->getId()] );
         }

        return $this->render('resetting_password/request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset/{id}", name="resetting")
     */
    public function Reset(
        int $id,
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(ResetPasswordType::class);

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

            return $this->redirectToRoute('app_login');
        }

        return $this->render('resetting_password/resetting_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
