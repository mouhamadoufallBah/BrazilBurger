<?php

namespace App\DataFixtures;

use App\Entity\Gestionnaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionnaireFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 3; $i++) {
            $data = new Gestionnaire();
            $data->setNomComplet($faker->name())
                ->setEmail(strtolower("gestionnaire") . $i . "@gmail.com")
                ->setMatricule(strtoupper('ges000') . $i);
            $plainPassword = "passer@123";
            $passwordEncode = $this->encoder->hashPassword($data, $plainPassword);
            $data->setPassword($passwordEncode);
            $this->addReference("Gestionnaire" . $i, $data);
            $manager->persist($data);

            $manager->flush();
        }
    }
}
