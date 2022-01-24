<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\Burger;
use App\Entity\Complement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 11; $i < 31; $i++) {
            // On genere 10 burgers
            $burger = new Burger();
            $burger->setPrix(2500)
                ->setNom('burger'.$i)
                ->setImage('burger'.$i.'.png');
            $manager->persist($burger);

            // On genere 10 complements
            $complement = new Complement();
            $complement->setPrix(1500)
                ->setImage('complement'.$i.'.png')
            ->setNom('complement' . $i);
            $manager->persist($complement);

            // On genere 10 menus avec burgers et complements
            $menu = new Menu();
            $menu->setNom("Menu" . $i)
                ->setImage("menu" . $i . ".png")
                ->addComplement($complement)
                ->addBurger($burger)
                ->getPrix();
            $manager->persist($menu);
            $manager->flush();
        }

    }
}
