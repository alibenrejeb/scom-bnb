<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Afficher tous les annonces
     *
     * @Route("/ads", name="app_ads_index")
     *
     * @return Response
     */
    public function index(AdRepository $adRepository): Response
    {
        $ads = $adRepository->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="app_ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad): Response
    {    
        return $this->render('ad/show.html.twig', ['ad' => $ad]);
    }
}
