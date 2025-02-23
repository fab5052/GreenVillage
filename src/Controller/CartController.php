<?php

// namespace App\Controller;

// use App\Entity\Product;
// use App\Repository\ProductRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;


// #[Route('/cart', 'cart_')]
// class CartController extends AbstractController
// {

   
   
    // private ProductRepository $productRepository;
    // private ImageRepository $imageRepository;
    // private TvaRepository $tvaRepository;
   
    // public function __construct(
    //     ProductRepository $productRepository,
    //     ImageRepository $imageRepository,
    //     TvaRepository $tvaRepository,
    // ) {
    
    //     $this->productRepository = $productRepository;
    //     // $this->imageRepository = $imageRepository;
    //     // $this->tvaRepository = $tvaRepository;
    // 
    // private function productDetails(Product $product, int $quantity): array
    // {
    //    // $product = $productRepository->findAll();

    //     $tvaRate = $product->getTva()?->getRate() ?? 0;
    //     $priceWithTva = $product->getPrice() * (1 + $tvaRate / 100);
    //     $total = $priceWithTva * $quantity;
    //     $totalTaxes = ($priceWithTva - $product->getPrice()) * $quantity;

    //     return [
    //         //'price' => $priceWithTva,
    //         'priceWithTva' => $priceWithTva,
    //         'total' => $total,
    //         'totalTaxes' => $totalTaxes,
    //     ];
    
//     #[Route('/', name: 'cart_index')]
//    public function index(SessionInterface $session, ProductRepository $productRepository)

//         $panier = $session->get("panier", []);
//         dump($session->get("panier"));
//         // On "fabrique" les données
//         $dataPanier = [];
//         $total = 0;

//         foreach($panier as $id => $quantity){
//             $product = $productRepository->find($id);
        
//             if (!$product) {
//                 $this->addFlash('error', "Le produit avec l'ID '$id' n'existe pas.");
//                 return $this->redirectToRoute('cart_index');
//             }
        
//             $dataPanier[] = [
//                 "products" => $product,
//                 "quantity" => $quantity,
//             ];
//             $total += $product->getPrice() * $quantity;
           
//         }

//         return $this->render('cart/index.html.twig', compact("dataPanier", "total"));

    
//     #[Route("/add/{id}", name: 'cart_add')]
//     public function add(Product $product, SessionInterface $session)
//     {
//         $panier = $session->get("panier", []);
//         $id = $product->getid();

//         if(!empty($panier[$id])){
//             $panier[$id]++;
//         }else{
//             $panier[$id] = 1;
//         }

//         // On sauvegarde dans la session
//         $session->set("panier", $panier);

//         return $this->redirectToRoute("cart_index");
//     }

//     #[Route('/remove/{id}', name: 'cart_remove')]
//     public function remove(Product $product, SessionInterface $session)
//     {
//         // On récupère le panier actuel
//         $panier = $session->get("panier", []);
//         $id = $product->getid();

//         if(!empty($panier[$id])){
//             if($panier[$id] > 1){
//                 $panier[$id]--;
//             }else{
//                 unset($panier[$id]);
//             }
//         }

//         // On sauvegarde dans la session
//         $session->set("panier", $panier);

//         return $this->redirectToRoute("cart_index");
//     }

//     #[Route("/delete/{id}", name: "cart_delete")]
//     public function delete(Product $product, SessionInterface $session)
//     {
//         // On récupère le panier actuel
//         $panier = $session->get("panier", []);
//         $id = $product->getid();

//         if(!empty($panier[$id])){
//             unset($panier[$id]);
//         }

//         // On sauvegarde dans la session
//         $session->set("panier", $panier);

//         return $this->redirectToRoute("cart_index");
//     }



//     #[Route("/delete", name: "cart_delete_all")]
//     public function deleteAll(SessionInterface $session)
//     {
//         $session->remove("panier");

//         return $this->redirectToRoute("cart_index");
//     } 


namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderDetails; 
use App\Entity\Image;
use App\Entity\Tva;
use App\Repository\ProductRepository;
use App\Repository\ImageRepository;
use App\Repository\TvaRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/cart', 'cart_')]
class CartController extends AbstractController
{
    private ProductRepository $productRepository;
    private ImageRepository $imageRepository;
    private TvaRepository $tvaRepository;

    public function __construct(
        ProductRepository $productRepository,
        ImageRepository $imageRepository,
        TvaRepository $tvaRepository,
    ) {
    
        $this->productRepository = $productRepository;
        $this->imageRepository = $imageRepository;
        $this->tvaRepository = $tvaRepository;
    }
    private function calculateProductDetails(Product $product, int $quantity): array
    {
       // $product = $productRepository->find($id);

        $tvaRate = $product->getTva()?->getRate() ?? 0;
        $totalTva = $product->getPrice() * ($tvaRate / 100);
        $totalHT = $product->getPrice() * $quantity;
        $total = ($totalTva + $product->getPrice()) * $quantity;

        
        return [
            //'priceWithTva' => $priceWithTva,
            'totalTva' => $totalTva,
            'total' => $total,
            'totalHT' => $totalHT,

        ];
    }

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
 
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour voir votre panier.');
                return $this->redirectToRoute('app_login');
            }
            $product = $this->productRepository->findAll();

    
            $panier = $session->get('panier', []);
           
            $productDetails =[];
            $dataPanier = [];
            $total = 0;
            $totalTva = 0;
            $totalHT = 0;
            $images = []; 
            
            
            foreach ($panier as $id => $quantity) {
               
                $product = $productRepository->find($id);

                if ($product) {
                  $productDetails = $this->calculateProductDetails($product, $quantity);
                   $total += $productDetails['total'];
                   $totalTva += $productDetails['totalTva'];
                    
                  // $images = $this->imageRepository->findBy(['product' => $product]);
    
                    $dataPanier[] = [
                        'product' => $product,
                        'total' => $total,
                        'totalHT' => $totalHT,
                        'totalTva' => $totalTva,
                        'quantity' => $quantity,
                        'priceWithTva' => $productDetails['totalTva'],
                       //'images' => $images,                 
                    ];
                   // $total += $product->getPrice() * $quantity;
                }
            }
    
           $session->set('panier', $panier);

        // } catch (\Exception $e) {
        //     $this->addFlash('error', 'Une erreur est survenue.');
        //     return $this->redirectToRoute('cart_index');
        // }
    
        return $this->render('cart/index.html.twig', [
            'product' => $dataPanier,
            //'priceWithTva' => $productDetails['totalTva'],
            'total' => $total,
            'totalTva' => $totalTva,
            'totalHT' => $totalHT,
            // 'images' => $images,
            //$productDetails =[],
          //  $dataPanier => []
        ]);
   } 

   #[Route('/add/{id}', name: 'add')]
   public function add(Product $product, SessionInterface $session): Response
   {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }      
    
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/allRemove/{id}', name: 'allRemove')]
    public function allRemove(Product $product, SessionInterface $session): Response
    {

            //On récupère le panier actuel
            $panier = $session->get("panier", []);
            $id = $product->getId();
    
            if(!empty($panier[$id])){
                unset($panier[$id]);
            }
    
            $session->remove('panier');

            $this->addFlash('success', 'Votre panier a été vidé.');
        
            return $this->redirectToRoute('cart_index');
        }
        // $product = $productRepository->find($id);
    
        // if (!$product) {
        //     $this->addFlash('error', 'Produit introuvable.');
        //     return $this->redirectToRoute('cart_index');
        // }
    
        // $panier = $session->get('panier', []);
        // unset($panier[$product->getId()]);
        // $session->set('panier', $panier);
    
        // $session->clear();
        // $this->addFlash('success', 'Vous avez bien vidé votre panier.');
        // return $this->redirectToRoute('cart_index');

       // #[Route('/delete/{id}', name: 'delete_all')]
        #[Route('/delete-all', name: 'delete_all')]
        public function deleteAll(SessionInterface $session): Response
        {
            // On vide le panier
            $session->remove('panier');
        
            $this->addFlash('warning', 'Votre panier a été supprimé.');
        
            return $this->redirectToRoute('app_home'); // Remplace 'app_home' par la route de ton accueil
        }
        
    



    #[Route('/orders', name: 'orders_new')]
    public function order(SessionInterface $session, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        try {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
                return $this->redirectToRoute('app_login');
            }

            $panier = $session->get('panier', []);
            if (empty($panier)) {
                $this->addFlash('warning', 'Votre panier est vide.');
                return $this->redirectToRoute('cart_index');
            }

            $paiement = $session->get('paiement');
            if (empty($paiement)) {
                $this->addFlash('warning', 'Aucun moyen de paiement sélectionné.');
                return $this->redirectToRoute('cart_index');
            }

            $order = (new Order())
                ->setUser($this->getUser())
                ->setReference('reference')
                ->setPaymentMethod($paiement)
                ->setType('commande')
                ->setPaymentDate(new \DateTimeImmutable())
                ->setPaymentStatus('En Cours de traitement')
                ->setDate(new \DateTimeImmutable())
                ->setStatus('En Attente');

            $totalAmount = 0;
            $orderDetails = []; // Initialize as an array

            foreach ($panier as $id => $quantity) {
                $product = $entityManager->getRepository(Product::class)->find($id);
                if ($product) {
                    $productDetails = $this->calculateProductDetails($product, $quantity);
                    $totalAmount += $productDetails['total'];
            
                    $orderDetail = (new OrderDetails())
                        ->setOrder($id)
                        ->setProduct($product)
                        ->setQuantity($quantity)
                        ->setPrice($productDetails['priceWithTva']);
            
                    $orderDetails[] = $orderDetail; 
                    $entityManager->persist($orderDetail); 
                } else {
                    $this->addFlash('warning', "Le produit avec l'id ($id) n'existe pas et a été ignoré.");
                }
            }
            
            if (empty($orderDetails)) {
                $this->addFlash('warning', 'Aucun produit valide dans votre panier.');
                return $this->redirectToRoute('cart_index');
            }
            
            $order->setTotal($totalAmount);
            $entityManager->persist($order);
            $entityManager->flush(); 

            $mail->send(
                'no-reply@village-green.fr',
                $session->get('user')->getEmail(),
                'Votre commande sur le site Village Green',
                'recap',
                [
                    'orders' => $order,
                    'orderDetails' => $orderDetails,
                ]
            );

            $session->clear();
            $this->addFlash('success', 'Votre commande a été enregistrée avec succès.');

            return $this->redirectToRoute('app_profile');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
    }


    #[Route('/ChoiceMultipleDeliveryByCart', name: 'Choice_Multiple_Delivery_By_Cart')]
    public function ChoiceMultipleDeliveryByCart(SessionInterface $session): Response
    {
        try {
            $panier = $session->get('panier', []);
            if (empty($panier)) {
                $this->addFlash('warning', 'Votre panier est vide.');
                return $this->redirectToRoute('cart_index');
            }
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
                return $this->redirectToRoute('app_login');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }


        return $this->render('delivery/Choice_multiple_delivery.html.twig', []);
    }



    #[Route('/recap', name: 'recap')]
    public function recap(ProductRepository $productRepository, SessionInterface $session): Response
    {
        try {
            if (!$this->getUser()) {
                $this->addFlash('warning', 'Vous devez vous connecter pour voir votre récapitulatif.');
                return $this->redirectToRoute('app_login');
            }

            $panier = $session->get('panier', []);
            $address = $session->get('address');

            $dataProduct = array_filter(array_map(function ($id) use ($productRepository, $panier) {
                $product = $productRepository->find($id);
                return $product ? [
                    'product' => $product,
                    'quantity' => $panier[$id],
                    'label' => $productRepository->getLabel(),
                ] : null;
            }, array_keys($panier)));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('cart/recap.html.twig', [
            'recap' => [
                'products' => $dataProduct,
                'addresses' => $address,
            ],
        ]);
    }

}