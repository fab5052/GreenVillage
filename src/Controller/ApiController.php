<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\EventRepository;
use App\Controller\JsonObjectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function Search(Request $request, ProductRepository $productRepository): JsonResponse
    {
        try {
            $query = $request->query->get('q', '');
            $products = $productRepository->searchOneBySlug($query);

            return $this->json($products, 200, [], ['groups' => 'product:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Impossible de récupérer les produits'], 400);
        }
    }

// #[Route('/new/event/{id}', name: 'add_event', methods: ['POST'])]
// public function newEvent($id, Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): JsonResponse
// {
//     $event = $eventRepository->find($id);
    
//     if (!$event) {
//         return $this->json(['message' => 'Événement non trouvé'], 404);
//     }

//     $data = json_decode($request->getContent(), true);
//     $event->setTitle($data['title']);
//     $event->setStart(new \DateTime($data['start']));
//     $event->setEnd(isset($data['end']) ? new \DateTime($data['end']) : null);

//     $entityManager->flush();

//     return $this->json(['message' => 'Événement mis à jour']);
// }

// #[Route('/events/{id}', name: 'delete_event', methods: ['DELETE'])]
// public function deleteEvent($id, EventRepository $eventRepository, EntityManagerInterface $entityManager): JsonResponse
// {
//     $event = $eventRepository->find($id);

//     if (!$event) {
//         return $this->json(['message' => 'Événement non trouvé'], 404);
//     }

//     $entityManager->remove($event);
//     $entityManager->flush();

//     return $this->json(['message' => 'Événement supprimé']);
}
