<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class BurgerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//sansComplement
        for($i = 1; $i<31; $i++)
        {
            // On genere 30 burgers
            $burger = new Burger();
            $burger->setPrix(2500)
                ->setNom('burger'.$i)
                ->setImage('burger'.$i.'.png');
            $manager->persist($burger);

        }

        $manager->flush();
    }
}
