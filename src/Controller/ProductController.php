<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Rubric;
use App\Repository\ProductRepository;
use App\Repository\RubricRepository;
use App\Repository\OrderRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[Route('/product', name: 'product_')]
class ProductController extends AbstractController
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private RubricRepository $rubricRepository;
    private ImageRepository $imageRepository;
    private PaginatorInterface $paginator;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        RubricRepository $rubricRepository,
        ImageRepository $imageRepository,
        PaginatorInterface $paginator
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->rubricRepository = $rubricRepository;
        $this->imageRepository = $imageRepository;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'products', methods: ['GET'])]
    public function index(Request $request ): Response
    {
        try {
           // $product = $this->productRepository->findAll();
            $rubrics = $this->rubricRepository->findAll(); 
          //  $subrubrics = $this->$rubricRepository->findOneBy(['parent' => null]);
            $productsQuery = $this->productRepository->findAll();
            $query = $this->productRepository->createQueryBuilder('p')->getQuery();
            $paginatedProducts = $this->paginator->paginate(
                $query,
                $request->query->getInt('page', 1), // Page actuelle (par défaut : 1)
                12 // Nombre d'éléments par page
            );
            $orders = $this->orderRepository->findAll(); 
            $images = [];
     
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger les produits. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/products.html.twig', [
            //'products' => $productRepository->findAll(), 
            'orders' => $orders,
            'rubric' => [],
            'rubrics' => $rubrics,
            'images' => $images,
            'product' => $productsQuery,
            'products' => $paginatedProducts,
        ]);
    }

    #[Route('/{slug}', name: 'details', methods: ['GET'])]
    public function details($slug): Response
    {
        try {
            $product = $this->productRepository->findAll();
            $details = $this->productRepository->findOneBy( ['slug' => $slug]);
            $orders = $this->orderRepository->findAll();
            $images = $this->imageRepository->findAll();
            if (!$details) {
                throw $this->createNotFoundException('Produit introuvable.');
            }
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger le produit. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/details.html.twig', [
            'products' => $product,
            'product' => $details,
            'orders' => $orders,
            'images' => $images,
        ]);
    }

    #[Route('/rubric/{id}', name: 'byRubric', methods: ['GET'])]
    public function byRubric($id): Response
    {
        try {
            $rubrics = $this->rubricRepository->find($id);

            if (!$rubrics) {
                throw $this->createNotFoundException('Rubrique introuvable.');
            }

            $byRubric = $this->productRepository->findBy(['rubric' => $rubrics]);
            $images = $this->imageRepository->findAll();
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Impossible de charger les produits par rubrique.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/byRubric.html.twig', [
            'subrubric' => $rubrics,
            'images' => $images,
            'products' => $byRubric,
        ]);
    }
}