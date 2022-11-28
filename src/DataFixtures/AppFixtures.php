<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use Faker\Factory;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Mmo\Faker\PicsumProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');
        $faker->addProvider(new PicsumProvider($faker));
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstname('Jad')
            ->setLastname('Ben Rejeb')
            ->setEmail('jbenrejeb@gmail.com')
            ->setPassword($this->hasher->hashPassword($adminUser, 'Admin01!'))
            ->setPicture('https://randomuser.me/api/portraits/lego/5.jpg')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->addUserRole($adminRole);
        $manager->persist($adminRole);

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for ($i=1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = sprintf(
                'https://randomuser.me/api/portraits/%s/%s.jpg',
                ($genre == 'male') ? 'men' : 'women',
                $faker->numberBetween(1, 99)
            );

            $password = $this->hasher->hashPassword($user, 'Azerty01!');

            $user->setFirstname($faker->firstname($genre))
                ->setLastname($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPicture($picture)
                ->setPassword($password);

            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for($i=1; $i<=30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = $faker->picsumUrl(1000,400);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setCoverImage($coverImage)
                ->setAuthor($user);

            for($j=1; $j<=mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->picsumUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            //Gestion des réservations
            for($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months', '-4 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $durantion = mt_rand(3, 10);

                $endDate = (clone $startDate)->modify("+$durantion days");
                $amount = $ad->getPrice() * $durantion;

                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);

                $manager->persist($booking);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
