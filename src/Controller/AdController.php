<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
     * Créer une annonce
     *
     * @Route("/ads/new", name="app_ads_new")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $em->persist($image);
            }

            $em->persist($ad);
            $em->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('app_ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Editer une annonce
     *
     * @Route("/ads/{slug}/edit", name="app_ads_edit")
     *
     * @return Response
     */
    public function edit(Request $request, Ad $ad, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $em->persist($image);
            }

            $em->persist($ad);
            $em->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrée !"
            );

            return $this->redirectToRoute('app_ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
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
