<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Rubric;
use App\Repository\ProductRepository;
use App\Repository\RubricRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product', name: 'product_')]
class ProductController extends AbstractController
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private RubricRepository $rubricRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        RubricRepository $rubricRepository,
        PaginatorInterface $paginator
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->rubricRepository = $rubricRepository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'products', methods: ['GET'])]
    public function index(Request $request): Response
    {
        try {
            
            $rubrics = $this->rubricRepository->findAll();
           // $productsQuery = $this->productRepository->findAll();
            $query = $this->productRepository->createQueryBuilder('p')->getQuery();
            $paginatedProducts = $this->paginator->paginate(
                $query,
                $request->query->getInt('page', 1), // Page actuelle (par défaut : 1)
                12 // Nombre d'éléments par page
            );
            $orders = $this->orderRepository->findAll(); 
            // if (!$orders || !$rubrics || !$paginatedProducts) {
            //     $this->addFlash('error', 'Certaines données sont manquantes.');
            //     return $this->redirectToRoute('app_home');
            // }          

        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger les produits. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/products.html.twig', [
            'orders' => $orders,
            'rubrics' => $rubrics,
            'products' => $paginatedProducts,
        ]);
    }

    #[Route('/{slug}', name: 'productDetails', methods: ['GET'])]
    public function productDetails(string $slug): Response
    {
        try {
            $productDetails = $this->productRepository->findOneBy(['slug' => $slug]);
            $orders = $this->orderRepository->findAll();
            if (!$productDetails) {
                throw $this->createNotFoundException('Produit introuvable.');
            }
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger le produit. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/productDetails.html.twig', [
            'product' => $productDetails,
        ]);
    }

    #[Route('/rubric/{slug}', name: 'by_rubric', methods: ['GET'])]
    public function ByRubric(string $slug): Response
    {
        try {
            $rubric = $this->rubricRepository->findOneBy(['slug' => $slug]);

            if (!$rubric) {
                throw $this->createNotFoundException('Rubrique introuvable.');
            }

            $productsByRubric = $this->productRepository->findBy(['rubric' => $rubric]);
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger les produits par rubrique.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/products_by_rubric.html.twig', [
            'rubric' => $rubric,
            'products' => $productsByRubric,
        ]);
    }
}