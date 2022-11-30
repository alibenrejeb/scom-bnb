<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    #[Route('/admin/ads', name: 'app_admin_ads_index')]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $adRepository->findAll(),
        ]);
    }

    
    /**
     * Permet d'afficher leformulaire d'édition
     *
     * @param Ad $ad
     * @return Response
     */
    #[Route('/admin/ads/{id}/edit', name: 'app_admin_ads_edit')]
    public function edit(Ad $ad, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @param Ad $ad
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/admin/ads/{id}/delete', name: 'app_admin_ads_delete')]
    public function delete(Ad $ad, EntityManagerInterface $em): Response
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations"
            );
        } else {
            $em->remove($ad);
            $em->flush();

            $this->addFlash(
                'danger',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée"
            );
        }

        return $this->redirectToRoute("app_admin_ads_index");
    }
}
