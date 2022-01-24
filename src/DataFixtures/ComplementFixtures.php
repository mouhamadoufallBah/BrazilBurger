<?php

namespace App\DataFixtures;

use App\Entity\Complement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ComplementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i<31; $i++)
        {
            // On genere 30 burgers
            $complement = new Complement();
            $complement->setPrix(1500)
                ->setNom('complement'.$i)
                ->setImage('complement'.$i.'.png');
            $manager->persist($complement);

        }

        $manager->flush();
    }
}
