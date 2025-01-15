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
            // Récupérer les rubriques principales (celles sans parent)
            $rubrics = $entityManager->getRepository(Rubric::class)->findBy(['parent_id' => null]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Impossible de charger les rubriques. Veuillez réessayer plus tard.');
            $rubrics = [];
        }

        return $this->render('rubric/rubrics.html.twig', [
            'rubrics' => $rubrics,
        ]);
    }
}