<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Mmo\Faker\PicsumProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');
        $faker->addProvider(new PicsumProvider($faker));

        for($i=1; $i<=30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = $faker->picsumUrl(1000,400);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad->setTitle($title)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setCoverImage($coverImage);

            for($j=1; $j<=mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->picsumUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }
}