<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="app_account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $erreur = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $erreur !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="app_account_register")
     *
     * @return Response
     */
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return $this->redirectToRoute('app_account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/update-password", name="app_account_update_password")
     *
     * @return Response
     */
    public function updatePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $passwordUpdate = new PasswordUpdate();

        /**
         * @var User
         */
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier le oldPassword du formulaire soit le même que le password de l'user
            if (!\password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')
                    ->addError(
                        new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel")
                    );
            } else {
                $hash = $hasher->hashPassword($user, $passwordUpdate->getNewPassword());
                $user->setPassword($hash);
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifé !'
                );
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('account/update-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="app_account_logout")
     *
     * @return void
     */
    public function logout() {}
}
