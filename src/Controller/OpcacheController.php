<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Amnuts\Opcache\Service;
use ApiPlatform\Metadata\ApiResource;

// assuming location of: /var/www/html/opcache.php
// require_once __DIR__ . '/../vendor/autoload.php';

// // specify any options you want different from the defaults, if any
// $options = [/* ... */];

// // setup the class and pass in your options, if you have any
// $opcache = (new Service($options))->handle();

#[ApiResource]
class OpcacheController extends AbstractController
{
    #[Route('/opcache-gui', name: 'opcache-gui')]
    public function index(): Response
    {
        // $user = $this->getUser();
        // if (!$user || !$this->isGranted('ROLE_ADMIN')) {
        //     return $this->redirectToRoute('app_login');
        // }
        // Chemin vers le fichier principal de Opcache GUI
        $opcacheGuiPath = __DIR__ . '/../../public/opcache-gui/index.php';

        if (!file_exists($opcacheGuiPath)) {
            throw new \Exception('Opcache GUI is not installed or path is incorrect.');
        }

        // Inclut le fichier PHP directement (sort du rendu Twig habituel)
        ob_start();
        include $opcacheGuiPath;
        $content = ob_get_clean();

        return new Response($content);
    }
}