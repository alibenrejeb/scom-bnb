<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Services\PaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    #[Route('/admin/bookings/{page<\d+>?1}', name: 'app_admin_bookings_index')]
    public function index(PaginatorService $paginator, $page): Response
    {
        $paginator->setEntityClass(Booking::class);

        return $this->render('admin/booking/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    #[Route('/admin/bookings/{id}/edit', name: 'app_admin_bookings_edit')]
    public function edit(Booking $booking, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $em->persist($booking);
            $em->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute('app_admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/bookings/{id}/delete', name: 'app_admin_bookings_delete')]
    public function delete(Booking $booking, EntityManagerInterface $em)
    {
        $id = $booking->getId();
        $em->remove($booking);
        $em->flush();

        $this->addFlash(
            'danger',
            "La réservation numéro {$id} a bien été supprimée"
        );

        return $this->redirectToRoute('app_admin_bookings_index');
    }
}
