<?php

namespace App\DataFixtures;

use App\Entity\Products;
use App\Entity\User;
use App\Entity\Details;
use App\Entity\Orders;
use App\Entity\OrderDetails;
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
        // $product = new Product();
        // $manager->persist($product);

        // $manager->flush();
    

    if (isset($user) && is_array($user)) {
        foreach ($user as $userData) {
            $userDB = new User();
            $userDB 
                ->setEmail($userData['email']) 
                ->setLastname($userData['nom'])
                ->setFirstname($userData['prenom'])                
                ->setAddress($userData['adresse'])
                ->setZipcode($userData['cp'])
                ->setCity($userData['ville']);
            $userDB->setIsVerified($userData[false]);
            $userDB->setPassword(
                $this->passwordEncoder->hashPassword($userDB, 'secret')
            );
            $userDB->setCreatedAt(new \DateTimeImmutable);
            $userDB->setRoles(['ROLE_CLIENT']);
            $manager->persist($userDB);
        }
    }

    $admin = new User();
    $admin->setEmail('admin@thedistrict.fr');
    $admin->setFirstname('Fabrice');
    $admin->setLastname('Beaujois');    
    $admin->setAddress('12 rue du port');
    $admin->setZipcode('80850');
    $admin->setCity('Berto');
    $admin->setPassword(
        $this->passwordEncoder->hashPassword($admin, 'fb975052')
    );
    $admin->setRoles(['ROLE_ADMIN']);
    
    $manager->persist($admin);
    $manager->flush();
}


}
