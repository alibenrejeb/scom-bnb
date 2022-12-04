<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getStats() {
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $comments = $this->getCommentsCount();
        $bookings = $this->getBookingsCount();
    
        return compact('users', 'ads', 'comments', 'bookings');
    }

    public function getUsersCount()
    {
        return $this->entityManager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount()
    {
        return $this->entityManager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->entityManager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->entityManager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getAdsStats($direction)
    {
        return $this->entityManager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstname, u.lastname, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }
}
