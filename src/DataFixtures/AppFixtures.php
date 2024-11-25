<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@example.com');
        $user->setPassword('password');

        $manager->persist($user); // Sauvegarde en mémoire

        // Ajouter d'autres entités si nécessaire...

        $manager->flush(); // Envoie tout dans la base de données


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
