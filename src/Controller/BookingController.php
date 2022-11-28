<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/ads/{slug}/book', name: 'app_booking_create')]
    #[IsGranted("ROLE_USER")]
    public function booking(Ad $ad, Request $request, EntityManagerInterface $em): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);

            // Si les dates ne sont pas disponibles, message d'erreur
            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi ne peuvent être réservées: elle sont déjà prise."
                );
            } else {
                $em->persist($booking);
                $em->flush();

                return $this->redirectToRoute('app_booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking/{id}', name: 'app_booking_show')]
    #[IsGranted("ROLE_USER")]
    public function show(Booking $booking)
    {
         return $this->render('booking/show.html.twig', [
             'booking' => $booking,
         ]);
    }
}
