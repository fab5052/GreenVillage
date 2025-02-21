<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $total = 0;
        $totalTaxes = 0;
        

        foreach ($cart as $slug => $quantity) {

            $product = $productRepository->findOneBy(['slug' => $slug]);
           
            $query = $productRepository->createQueryBuilder('p')->getQuery();
 $productsQuery = $productRepository->findAll();

            if (!$product) {
                $this->addFlash('error', "Produit introuvable : $slug");
                continue;
            }

            $priceHT = $product->getPrice() * $quantity;
            $priceTTC = $priceHT * 1.2; // TVA 20%

            $cartWithData[] = [
                'product' => $product,
                'quantity' => $quantity,
                'priceWithTva' => $priceTTC
            ];

            $total += $priceHT;
            $totalTaxes += $priceTTC;
        }


        
        return $this->render('cart/index.html.twig', [
            dump($session->get('cart')),
'product' => $productsQuery,
            'products' => $cartWithData,
            'total' => $total,
            'totalTaxes' => $totalTaxes
        ]);

        
    }
    

    #[Route('/cart/add/{slug}', name: 'app_add')]
    public function add($slug, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            $this->addFlash('error', "Produit introuvable.");
            return $this->redirectToRoute('app_cart');
        }

        $cart = $session->get('cart', []);

        $cart[$slug] = ($cart[$slug] ?? 0) + 1;

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{slug}', name: 'app_remove')]
    public function remove($slug, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            $this->addFlash('error', "Produit introuvable.");
            return $this->redirectToRoute('app_cart');
        }

        $cart = $session->get('cart', []);

        if (!empty($cart[$slug])) {
            if ($cart[$slug] > 1) {
                $cart[$slug]--;
            } else {
                unset($cart[$slug]);
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/allRemove/{slug}', name: 'app_allRemove')]
    public function removeAll($slug, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            $this->addFlash('error', "Produit introuvable.");
            return $this->redirectToRoute('app_cart');
        }

        $cart = $session->get('cart', []);

        unset($cart[$slug]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
}



    // #[Route('/orders', name: 'orders_index')]
    // public function order(SessionInterface $session, EntityManagerInterface $entityManager, SendMailService $mail): Response
    // {
    //     try {
    //         if (!$this->getUser()) {
    //             $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
    //             return $this->redirectToRoute('app_login');
    //         }

    //         $panier = $session->get('panier', []);
    //         if (empty($panier)) {
    //             $this->addFlash('warning', 'Votre panier est vide.');
    //             return $this->redirectToRoute('cart_index');
    //         }

    //         $paiement = $session->get('paiement');
    //         if (empty($paiement)) {
    //             $this->addFlash('warning', 'Aucun moyen de paiement sélectionné.');
    //             return $this->redirectToRoute('cart_index');
    //         }

    //         $order = (new Order())
    //             ->setUser($this->getUser())
    //             ->setReference('GreVil:' . uniqid() . mt_rand(100, 999))
    //             ->setPaymentMethod($paiement)
    //             ->setType('commande')
    //             ->setPaymentDate(new \DateTimeImmutable())
    //             ->setPaymentStatus('En Cours de traitement')
    //             ->setDate(new \DateTimeImmutable())
    //             ->setStatus('En Attente');

    //         $totalAmount = 0;
    //         $orderDetails = []; // Initialize as an array

    //         foreach ($panier as $id => $quantity) {
    //             $product = $entityManager->getRepository(Product::class)->find($id);
    //             if ($product) {
    //                 $productDetails = $this->calculateProductDetails($product, $quantity);
    //                 $totalAmount += $productDetails['total'];
            
    //                 $orderDetail = (new OrderDetails())
    //                     ->setOrder($id)
    //                     ->setProduct($product)
    //                     ->setQuantity($quantity)
    //                     ->setPrice($productDetails['priceWithTax']);
            
    //                 $orderDetails[] = $orderDetail; 
    //                 $entityManager->persist($orderDetail); 
    //             } else {
    //                 $this->addFlash('warning', "Le produit avec l'ID ($id) n'existe pas et a été ignoré.");
    //             }
    //         }
            
    //         if (empty($orderDetails)) {
    //             $this->addFlash('warning', 'Aucun produit valide dans votre panier.');
    //             return $this->redirectToRoute('cart_index');
    //         }
            
    //         $order->setTotal($totalAmount);
    //         $entityManager->persist($order);
    //         $entityManager->flush(); 

    //         $mail->send(
    //             'no-reply@village-green.fr',
    //             $session->get('user')->getEmail(),
    //             'Votre commande sur le site Village Green',
    //             'recap',
    //             [
    //                 'order' => $order,
    //                 'orderDetails' => $orderDetails,
    //             ]
    //         );

    //         $session->clear();
    //         $this->addFlash('success', 'Votre commande a été enregistrée avec succès.');

    //         return $this->redirectToRoute('app_profile');
    //     } catch (\Exception $e) {
    //         $this->addFlash('error', 'Une erreur est survenue.');
    //         return $this->redirectToRoute('cart_index');
    //     }
    // }


    // #[Route('/ChoiceMultipleDeliveryByCart', name: 'Choice_Multiple_Delivery_By_Cart')]
    // public function ChoiceMultipleDeliveryByCart(SessionInterface $session): Response
    // {
    //     try {
    //         $panier = $session->get('panier', []);
    //         if (empty($panier)) {
    //             $this->addFlash('warning', 'Votre panier est vide.');
    //             return $this->redirectToRoute('cart_index');
    //         }
    //         if (!$this->getUser()) {
    //             $this->addFlash('warning', 'Vous devez vous connecter pour passer une commande.');
    //             return $this->redirectToRoute('app_login');
    //         }
    //     } catch (\Exception $e) {
    //         $this->addFlash('error', 'Une erreur est survenue.');
    //         return $this->redirectToRoute('cart_index');
    //     }


    //     return $this->render('delivery/Choice_multiple_delivery.html.twig', []);
    // }



    // #[Route('/recap', name: 'recap')]
    // public function recap(ProductRepository $productRepository, SessionInterface $session): Response
    // {
    //     try {
    //         if (!$this->getUser()) {
    //             $this->addFlash('warning', 'Vous devez vous connecter pour voir votre récapitulatif.');
    //             return $this->redirectToRoute('app_login');
    //         }

    //         $panier = $session->get('panier', []);
    //         $address = $session->get('address');

    //         $dataProduct = array_filter(array_map(function ($id) use ($productRepository, $panier) {
    //             $product = $productRepository->find($id);
    //             return $product ? [
    //                 'product' => $product,
    //                 'quantity' => $panier[$id],
    //                 'label' => $productRepository->getLabel(),
    //             ] : null;
    //         }, array_keys($panier)));
    //     } catch (\Exception $e) {
    //         $this->addFlash('error', 'Une erreur est survenue.');
    //         return $this->redirectToRoute('cart_index');
    //     }
    //     return $this->render('cart/recap.html.twig', [
    //         'recap' => [
    //             'products' => $dataProduct,
    //             'addresses' => $address,
    //         ],
    //     ]);
    // }

