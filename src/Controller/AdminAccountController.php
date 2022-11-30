<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    #[Route('/admin/login', name: 'app_admin_account_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $erreur = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $erreur !== null,
            'username' => $username
        ]);
    }

    #[Route('/admin/logout', name: 'app_admin_account_logout')]
    public function logout() {}
}
