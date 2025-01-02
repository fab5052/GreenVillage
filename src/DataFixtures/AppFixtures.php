<?php

namespace App\DataFixtures;

use App\Entity\Rubric;
use App\Entity\Product;
use App\Entity\InfoSuppliers;
use App\Entity\OrdersDetails;
use App\Entity\Orders;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Tva;
use App\Entity\Enum\UserRole;
use Doctrine\DBAL\Types\DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\EventListener\SlugListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
   private UserPasswordHasherInterface $passwordEncoder;

public function __construct(UserPasswordHasherInterface $passwordEncoder)
{
    $this->passwordEncoder = $passwordEncoder;
}

public function load(ObjectManager $manager): void
{
    
    $admin = new User();
    $admin->setEmail('admin@greenvillage.net')
          ->setFirstname('Fabrice')
          ->setLastname('Beaujois')
          ->setIsVerified(true)
          ->setAddress('12 rue du port')
          ->setZipcode('80850')
          ->setCity('Berto')
          ->setPassword($this->passwordEncoder->hashPassword($admin, 'fb975052'))
          ->setRoles(['ROLE_ADMIN']);

    $manager->persist($admin);
    
    $faker = \Faker\Factory::create('fr_FR');

    // Création des utilisateurs


    $users = [];
    for ($i = 0; $i < 5; $i++) {
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable())
             ->setEmail($faker->email)
             ->setLastname($faker->lastName)
             ->setFirstname($faker->firstName)
             ->setRoles(['ROLE_USER'])
             ->setAddress($faker->address)
             ->setZipcode($faker->postcode)
             ->setCity($faker->city)
             ->setPassword($this->passwordEncoder->hashPassword($user, 'secret'));
        $manager->persist($user);
        $users[] = $user;
    }
    $manager->flush(); // Assurez-vous que les utilisateurs sont persistés

    // Création des rubriques
    $rubrics = [];
    $rubricLabels = ['vent', 'percussions', 'cordes', 'electronique'];

    foreach ($rubricLabels as $label) {
        $rubric = new Rubric();
        $rubric->setLabel($label)
               ->setSlug($label)
               ->setImage($faker->imageUrl);
        $manager->persist($rubric);
        $rubrics[] = $rubric;
    }
    $manager->flush(); // Assurez-vous que les rubriques sont persistées

    // Création des sous-rubriques
    $subRubricLabels = ['saxo', 'trompette', 'batterie', 'tamtam', 'guitare', 'piano', 'synthétiseur', 'amplificateur'];

    foreach ($subRubricLabels as $label) {
        $subRubric = new Rubric();
        $subRubric->setLabel($label)
                  ->setSlug($label)
                  ->setImage($faker->imageUrl)
                  ->setDescription($faker->paragraph)
                  ->setParent($faker->randomElement($rubrics)); // Associe une rubrique parent existante
        $manager->persist($subRubric);
    }
    $manager->flush(); // Assurez-vous que les sous-rubriques sont persistées

    // Création des fournisseurs
    $supplierTypes = ['constructeur', 'importateur'];
    $suppliers = [];
    for ($i = 0; $i < 5; $i++) {
        $supplier = new InfoSuppliers();
        $supplier->setType($faker->randomElement($supplierTypes))
                 ->setStatus('Active')
                 ->setReference("Suppliers:" . mt_rand(10000, 99999))
                 ->setUser($faker->randomElement($users));
        $manager->persist($supplier);
        $suppliers[] = $supplier;
    }
    $manager->flush(); // Assurez-vous que les fournisseurs sont persistés

    for ($i = 0; $i < 10; $i++) {
        $tva = new Tva(); 
        $tva->setRate('18.60');
    //         >setProduct($faker->randomElement($manager->getRepository(Product::class)->findAll()));
        $manager->persist($tva);
        }
    $manager->flush();
    

    // Création des produits
    for ($i = 0; $i < 50; $i++) {
        $product = new Product();
        $product->setLabel($faker->sentence)
                ->setSlug($faker->slug)
                ->setStock(mt_rand(1, 100))
                ->setPrice(mt_rand(1, 100))
                ->setReference("GrVi:" . mt_rand(10000, 99999))
                ->setDescription($faker->paragraph)
                ->setWeight($faker->randomFloat(2, 0, 100))
                ->setInfoSupplier($faker->randomElement($suppliers))
                ->setRubric($faker->randomElement($rubrics)) // Associe une rubrique existante
                ->setTva($tva)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($product);
    }
    $manager->flush();

    // Création des images
    for ($i = 0; $i < 10; $i++) {
        $image = new Image();
        $image->setImg($faker->imageUrl)
              ->setProduct($faker->randomElement($manager->getRepository(Product::class)->findAll()));
        $manager->persist($image);
    }
    $manager->flush();
}
}




## Services

    #Creation de services lier a des utilisateurs

    // $servicetype = ['apres-vente', 'commercial', 'equipe'];

    // for ($i = 0; $i < 10; $i++) {
    //     try {
    //         $service = new Service();
    //         $service->setType($servicetype[mt_rand(0, 2)]);
    //         $manager->persist($service);
    //     } catch (\Exception $e) {
    //         throw new \RuntimeException('Erreur lors de la création d\'un service', 0, $e);
    //     }
    // }

    // try {
    //     $manager->flush();
    // } catch (\Exception $e) {
    //     throw new \RuntimeException('Erreur lors de la sauvegarde des services', 0, $e);
    // }


