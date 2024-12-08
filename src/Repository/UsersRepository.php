<?php

namespace App\Repository;

use App\Entity\Users;
use App\Enum\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $users, string $newHashedPassword): void
    {
        if (!$users instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $users::class));
        }


        $user = new Users();
        $user->setPassword($newHashedPassword);
        $user->getIsVerified();
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();


        // if ($user->getRole() === UserRole::ADMIN) {
        //     echo 'This user is an admin.';
        // }
        
        // $user =new Users();
        // $user->setUsername('john_doe');
        // $user->setPassword('secure_password');
        // $user->setRole(UserRole::ADMIN);
    }



    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
