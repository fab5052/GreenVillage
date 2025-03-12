<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[Route('/api', name: 'api_')]
//#[Route('/event', name: 'event')]
class EventController extends AbstractController
{
    #[Route('/list/create', name: 'list_create', methods: ['GET', 'POST'])]
    public function listOrCreate(int $id, EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // if (!$this->getUser()) {
        //     $this->addFlash('warning', 'Vous devez vous connecter pour voir votre panier.');
        //     return $this->redirectToRoute('app_login');
        // }
        
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['title'], $data['start'])) {
                return $this->json(['message' => 'Données incomplètes'], 400);
            }

            $event = new Event();
            $event->setTitle($data['title']);
            $event->setStart(new \DateTime($data['start']));
            $event->setEnd(isset($data['end']) ? new \DateTime($data['end']) : null);
            $event->setColor($data['color'] ?? '#3788d8');

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->json($event, 201);
        }

        $events = $eventRepository->findAll();
        return $this->json($events, 200);
    }

    #[Route('/event/{id}', name: 'update_event', methods: ['PUT'])]
public function updateEvent(int $id, Request $request, EventRepository $eventRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $event = $eventRepository->find($id);
    if (!$event) {
        return $this->json(['message' => 'Événement non trouvé'], 404);
    }

    $data = json_decode($request->getContent(), true);
    
    if (isset($data['title'])) {
        $event->setTitle($data['title']);
    }
    if (isset($data['start'])) {
        $event->setStart(new \DateTime($data['start']));
    }
    if (isset($data['end'])) {
        $event->setEnd(new \DateTime($data['end']));
    }

    $entityManager->flush();

    return $this->json($event);
}

    #[Route('/{id}', name: 'delete_event', methods: ['DELETE'])]
    public function deleteEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $event = $eventRepository->find($id);
        if (!$event) {
            return $this->json(['message' => 'Événement non trouvé'], 404);
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->json(['message' => 'Événement supprimé']);
    }
}
