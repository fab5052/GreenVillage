<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\SendMailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact', name: 'contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function contact(Request $request, SendMailService $sendMailService): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Votre demande de contact a bien été envoyée. Vous avez reçu un email de confirmation.');
            //mail pour le client
            $sendMailService->send(
                'no-reply@Village-green.fr',
                $form->get('email')->getData(),
                $form->get('subject')->getData(),
                'confirm_contact',
                ['form' => $form->getData()]
            );

            //mail pour l'admin
            $sendMailService->send(
                $form->get('email')->getData(),
                'Service-Contact@Village-green.fr',
                $form->get('subject')->getData(),
                'contact_client',
                ['form' => $form->getData()]
            );
            return $this->redirectToRoute('VillageGreen_index');
        }



        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
