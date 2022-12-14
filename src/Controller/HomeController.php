<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(AdRepository $adRepository, UserRepository $userRepository) {
        return $this->render('home.html.twig', [
            'ads' => $adRepository->findBestAds(3),
            'users' => $userRepository->findBestUser()
        ]);
    }
}