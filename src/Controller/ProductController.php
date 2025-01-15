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
            $productsQuery = $this->productRepository->findAll();
            $paginatedProducts = $this->paginator->paginate(
                $productsQuery,
                $request->query->getInt('page', 1),
                12);
            $orders = $this->orderRepository->findAll(); 
            if (!$orders || !$rubrics || !$paginatedProducts) {
                $this->addFlash('error', 'Certaines données sont manquantes.');
                return $this->redirectToRoute('home');
            }          

        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger les produits. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('home');
        }

        return $this->render('product/products.html.twig', [
            'orders' => $orders,
            'rubrics' => $rubrics,
            'products' => $paginatedProducts,
        ]);
    }

    #[Route('/{slug}', name: 'details', methods: ['GET'])]
    public function details(string $slug): Response
    {
        try {
            $product = $this->productRepository->findOneBy(['slug' => $slug]);
            // $order = $this->orderRepository->findAll();
            if (!$product) {
                throw $this->createNotFoundException('Produit introuvable.');
            }
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger le produit. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/product_details.html.twig', [
            'product' => $product,
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