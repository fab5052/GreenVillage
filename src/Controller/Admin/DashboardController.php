<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Rubric;
use App\Entity\User;
use App\Entity\InfoSuppliers;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\RubricCrudController; // Ajoute cette ligne en haut du fichier


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user || !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Village Green Dashboard Admin');
    }

    public function configureMenuItems(): iterable
{
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Accueil', 'fas fa-arrow-left', '/');
        yield MenuItem::section('Gestion');
        //yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        //yield MenuItem::linkToCrud('Fournisseurs', 'fas fa-truck', InfoSuppliers::class);
        yield MenuItem::subMenu('Utlisateurs', 'fas fa-box-open')->setSubItems([
            MenuItem::linkToCrud('Liste des utilisateurs', 'fas fa-users', User::class)
        ]);
        yield MenuItem::subMenu('Rubriques', 'fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Liste des rubriques', 'fas fa-list', Rubric::class)
              
        ]);
        yield MenuItem::subMenu('Produits', 'fas fa-box-open')->setSubItems([
            MenuItem::linkToCrud('Liste des produits', 'fas fa-list', Product::class)

        ]);
    }           
}
