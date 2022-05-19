<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController {

    protected CartService $utils;
    protected ProductRepository $productRepository;

    public function __construct(CartService $utils, ProductRepository $productRepository) {
        $this -> utils = $utils;
        $this -> productRepository = $productRepository;
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', requirements: ['id' => "\d+"])]
    public function add(Request $request, $id): Response {

        $product = $this -> getProduct($id);

        if ($product -> getQuantity() <= 0)
            $this -> utils -> flashMessage(CartService::DANGER, "ERREUR: Le produit est en rupture de stock !");
        else
            $this -> utils -> addToCart($product);

        $route = $request -> headers -> get('referer');
        return $route == null ? $this -> redirectToRoute('app_cart') : $this -> redirect($request -> headers -> get('referer'));
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete', requirements: ['id' => "\d+"])]
    public function delete($id): Response {

        $product = $this -> getProduct($id);

        if (!array_key_exists($product -> getId(), $this -> utils -> getCart()))
            $this -> utils -> flashMessage(CartService::DANGER, "ERREUR: Ce produit n'est plus dans votre panier!");
        else
            $this -> utils -> removeToCart($product);

        return $this -> redirectToRoute('app_cart');
    }

    #[Route('/cart/decrement/{id}', name: "app_cart_decrement", requirements: ['id' => "\d+"])]
    public function decrement($id): Response {

       $product = $this -> getProduct($id);

       if (!array_key_exists($product -> getId(), $this -> utils -> getCart()))
           $this -> utils -> flashMessage(CartService::DANGER, "ERREUR: Ce produit n'est plus dans votre panier!");
       else
           $this -> utils -> decrementCart($product);

       return $this -> redirectToRoute('app_cart');
    }

    private function getProduct(int $id): Product {
        $product = $this -> productRepository -> find($id);

        if (empty($product))
            throw $this -> createNotFoundException("Le produit $id n'existe pas !");

        return $product;
    }

    #[Route('/cart', name: "app_cart")]
    public function cart(): Response {

        $products = $this -> utils -> getDetailedCartItems();
        $total = $this -> utils -> getTotal();

        return $this -> render('cart/cart.html.twig', [
            'cart' => $products,
            'total' => $total,
        ]);

    }

    #[Route('/cart/payment', name: "app_cart_payment")]
    public function payment(): Response {

        $this -> utils -> clearCart();

        return $this -> redirectToRoute('app_cart');
    }
}
