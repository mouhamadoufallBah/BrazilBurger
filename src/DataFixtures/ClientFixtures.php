<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker= Factory::create('fr_FR');

        for ($i=1; $i < 11; $i++) {
            $data=new Client;
            $data->setNomComplet($faker->name());
            $data->setTelephone($faker->phoneNumber())
                ->setEmail(strtolower("client").$i."@gmail.com");
            $plainPassword="Passer@123";
            $passwordEncode= $this->encoder->hashPassword($data,$plainPassword);
            $data->setPassword($passwordEncode);
            $this->addReference("Client".$i, $data);
            $manager->persist($data);
        }


        $manager->flush();
    }
}
