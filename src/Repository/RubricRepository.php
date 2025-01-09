<?php

namespace App\Repository;

use ApiPlatform\OpenApi\Model\Response;
use App\Entity\Rubric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\Response\ResponseStream;

/**
 * @extends ServiceEntityRepository<Rubric>
 */
class RubricRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubric::class);
    }


   
    /**
     * Returns the most recently created Rubric.
     *
     * @return Rubric|null
     */
    public function showLastRubric(): ?Rubric
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

//    public function showLastRubric(): Response
//    {
//        $lastRubric = $this->getDoctrine()
//            ->getRepository(Rubric::class)
//            ->findOneBy([], ['createdAt' => 'DESC']);
//    }

//    public function showLastRubric($lastRubric): 
//    {
//      $lastRubric = $this->getDoctrine()
//                ->getRepository(Rubric::class)
//                ->findOneBy([], ['createdAt' => 'DESC']);
         
//    }

// }
//    public function findOneBySomeField($value): ?Rubrique
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

