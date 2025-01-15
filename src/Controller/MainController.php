<?php

namespace App\Controller;

use App\Entity\Rubric;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            // Chargement des rubriques principales avec leurs sous-rubriques
            // $rubrics = $entityManager->getRepository(Rubric::class)
            //     ->createQueryBuilder('r')
            //     ->leftJoin('r.children', 'c')
            //     ->addSelect('c')
            //     ->where('r.parent IS NULL')
            //     ->getQuery()
            //     ->getResult();
            $rubrics = $entityManager->getRepository(Rubric::class)->findBy(['parent' => null]);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
            $rubrics = [];
        }
    
        return $this->render('main/index.html.twig', [
            'rubrics' => $rubrics,
        ]);
    }
}