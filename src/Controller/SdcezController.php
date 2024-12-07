<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SdcezController extends AbstractController
{
    #[Route('/sdcez', name: 'app_sdcez')]
    public function index(): Response
    {
        return $this->render('sdcez/index.html.twig', [
            'controller_name' => 'SdcezController',
        ]);
    }
}
