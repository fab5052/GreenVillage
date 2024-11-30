<?php

namespace App\Controller;

use Amnuts\Opcache\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpcacheController extends AbstractController
{
    #[Route('/opcache', name: 'app_opcache')]
    public function index(): Response
    {
        return $this->render('opcache/index.html.twig', [
            'controller_name' => 'OpcacheController',
        ]);
    }
}

// assuming location of: /var/www/html/opcache.php
require_once __DIR__ . '/../vendor/autoload.php';

// specify any options you want different from the defaults, if any
$options = [/* ... */];

// setup the class and pass in your options, if you have any
$opcache = (new Service($options))->handle();