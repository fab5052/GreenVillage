<?php

namespace App\DataFixtures;

use App\Entity\Rubric;
use App\Entity\Product;
use App\Entity\OrdersDetails;
use App\Entity\Orders;
use App\Entity\User;
// use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        // // $product = new Product();
        // // $manager->persist($product);

        // $manager->flush();

        if (isset($user) && is_array($user)) {
            foreach ($user as $userData) {
                $date = new \DateTimeImmutable($userData['created_at']);
                $userDB = new User();
                $userDB 
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setEmail($userData['email']) 
                    ->setLastname($userData['nom'])
                    ->setFirstname($userData['prenom'])
                    ->setRoles($userData['ROLE_USER'])
                    ->setAddress($userData['adresse'])
                    ->setZipcode($userData['cp'])
                    ->setCity($userData['ville']);
                $userDB->setPassword(
                    $this->passwordEncoder->hashPassword($userDB, 'secret')
                );
              //  $userDB->setCreatedAt(
                //    new \DateTimeImmutable()); // Définit la date actuelle
                $manager->persist($userDB);
            }
        }

        $admin = new User();
        $admin->setEmail('admin@greenvillage.fr');
        $admin->setFirstname('Fabrice');
        $admin->setLastname('Beaujois');
        $admin->setIsVerified(true);
        $admin->setAddress('12 rue du port');
        $admin->setZipcode('80850');
        $admin->setCity('Berto');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'fb975052')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);
        $manager->flush();    
    

    try {
        $faker = \Faker\Factory::create('fr_FR');
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la création de Faker', 0, $e);
    }

    #Creation de services lier a des utilisateurs

    $servicetype = ['apres-vente', 'commercial', 'equipe'];

    for ($i = 0; $i < 10; $i++) {
        try {
            $service = new \App\Entity\UserType();
            $service->setType($servicetype[mt_rand(0, 2)]);
            $manager->persist($service);
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur lors de la création d\'un service', 0, $e);
        }
    }

    try {
        $manager->flush();
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la sauvegarde des services', 0, $e);
    }

#suppliers
    $suppliertype = ['constructeur', 'importateur'];
    for ($i = 0; $i < 5; $i++) {
        try {
            $provider = new \App\Entity\SupplierDetails();
            $provider->setType($faker->randomElement($suppliertype));
            $provider->setStatus('Active');
            $provider->setRef("Fou:" . mt_rand(10000, 99999));
            $provider->setUser($faker->randomElement($manager->getRepository(\App\Entity\User::class)->findAll()));
            $manager->persist($provider);
        } catch (\Exception $e) {
        }
    }

    try {
        $manager->flush();
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la sauvegarde des fournisseurs', 0, $e);
    }


# Rubric, Product, image

$NameRubriques = ['vent', 'percussions', 'cordes'];

foreach ($NameRubriques as $label) {
    try {
        $rubrique = new \App\Entity\Rubric();
        $rubrique->setLabel($label);
        $rubrique->setSlug($label);
        $rubrique->setImage($faker->imageUrl);
        $rubrique->setDescription($faker->paragraph);


        $manager->persist($rubrique);
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la création d\'une rubrique', 0, $e);
    }
}

try {
    $manager->flush();
} catch (\Exception $e) {
    throw new \RuntimeException('Erreur lors de la sauvegarde des rubriques', 0, $e);
}


# Creation de Sousrubrique dans la BDD
$NameSubRubriques = ['batterie', 'guitare', 'piano', 'flute'];

foreach ($NameSubRubriques as $label) {
    try {
        $subrubrique = new \App\Entity\Rubric();
        $subrubrique->setLabel($label);
        $subrubrique->setSlug($label);
        $subrubrique->setImage($faker->imageUrl);
        $subrubrique->setDescription($faker->paragraph);
        $subrubrique->setParent($faker->randomElement($manager->getRepository(\App\Entity\Rubric::class)->findAll()));

        $manager->persist($subrubrique);
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la création d\'une sousrubrique', 0, $e);
    }

    try {
        $manager->flush();
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la sauvegarde des sousrubriques', 0, $e);
    }
}

# Creation d'une taxe dans la BDD
try {
    $tax = new \App\Entity\Tva();
    $tax->setRate('18.60');
    $manager->persist($tax);
} catch (\Exception $e) {
    throw new \RuntimeException('Erreur lors de la creation d\'une taxe', 0, $e);
}

try {
    $manager->flush();
} catch (\Exception $e) {
    throw new \RuntimeException('Erreur lors de la sauvegarde de la taxe', 0, $e);
}

# Creation de Produit dans la BDD
for ($i = 0; $i < 50; $i++) {
    try {
        $product = new \App\Entity\Product();
        $product->setLabel($faker->sentence);
        $product->setSlug($faker->slug);
        $product->setStock(mt_rand(1, 100));
        $product->setPrice(mt_rand(1, 100));
        $product->setReference("Inst:" . mt_rand(10000, 99999));
        $product->setDescription($faker->paragraph);
        $product->setWeight($faker->randomFloat(2, 0, 100));
        $product->setSuppliers($faker->randomElement($manager->getRepository(\App\Entity\SupplierDetails::class)->findAll()));
        $product->setRubric($faker->randomElement($manager->getRepository(\App\Entity\Rubric::class)->findAll()));
        $product->setTva($faker->randomElement($manager->getRepository(\App\Entity\Tax::class)->findAll()));
        $product->setCreatedAt(new DateTimeImmutable());
        $product->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($product);
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la création d\'un produit', 0, $e);
    }
}

try {
    $manager->flush();
} catch (\Exception $e) {
    throw new \RuntimeException('Erreur lors de la sauvegarde des produits', 0, $e);
}


# Creation d'image dans la BDD
for ($i = 0; $i < 10; $i++) {
    try {
        $image = new \App\Entity\Image();
        $image->setImg($faker->imageUrl);
        $image->setProduct($faker->randomElement($manager->getRepository(\App\Entity\Product::class)->findAll()));
        $manager->persist($image);
    } catch (\Exception $e) {
        throw new \RuntimeException('Erreur lors de la création d\'une image', 0, $e);
    }
}

try {
    $manager->flush();
} catch (\Exception $e) {
    throw new \RuntimeException('Erreur lors de la sauvegarde des images', 0, $e);
}
}
}






