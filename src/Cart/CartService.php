<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    public const CART = 'cart';

    protected const FLASHES = 'flashes';
    public const SUCCESS = 'success';
    public const DANGER = 'danger';

    protected EntityManagerInterface $manager;
    protected ProductRepository $productRepository;
    protected SessionInterface $session;

    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository, RequestStack $requestStack) {
        $this -> manager = $manager;
        $this -> productRepository = $productRepository;
        $this -> session = $requestStack -> getSession();
    }


    public function addToCart(Product $product) : void {
        $cart = $this -> getCart();

        $productId = $product -> getId();

        if (!array_key_exists($productId, $cart))
            $cart[$productId] = 0;

        $cart[$productId]++;

        $this -> setCart($cart);
        $product -> setQuantity($product -> getQuantity() - 1);

        $this -> manager -> flush();

        $this -> flashMessage(self::SUCCESS, "Le produit a bien été ajouté au panier !");
    }

    public function removeToCart(Product $product) : void {

        $cart = $this -> getCart();
        $productId = $product -> getId();

        $oldQuantity = $cart[$productId];

        unset($cart[$productId]);

        $this -> setCart($cart);
        $product -> setQuantity($product -> getQuantity() + $oldQuantity);

        $this -> manager -> flush();

        $this -> flashMessage(self::SUCCESS, "Le produit à bien été retiré de votre panier!");
    }

    public function decrementCart(Product $product): void {

        $cart = $this -> getCart();
        $productId = $product -> getId();

        $cart[$productId]--;

        if ($cart[$productId] == 0)
            unset($cart[$productId]);

        $this -> setCart($cart);
        $product -> setQuantity($product -> getQuantity() + 1);

        $this -> manager -> flush();

        $this -> flashMessage(self::SUCCESS, "Le produit à bien été retiré de votre panier!");
    }

    public function getCart(): array {
        return $this -> session -> get(self::CART, []);
    }

    public function setCart($cart): void {
        $this -> session -> set(self::CART, $cart);
    }

    public function clearCart(): void {
        $this -> setCart([]);
    }

    public function flashMessage(string $messageType, string $message): void {
        $flashBag = $this -> session -> getBag(self::FLASHES);
        $flashBag -> add($messageType, $message);
    }


    /**
     *
     * This function returns the total price of the cart.
     *
     * @return float
     */
    public function getTotal() : float {
        $total = 0;

        foreach ($this -> getCart() as $id => $quantity) {

            $product = $this -> productRepository -> find($id);

            if (!$product)
                continue;

            $total += $product -> getPrice() * $quantity;
        }

        return $total;

    }

    /**
     *
     * This function returns the entire cart with detailed information about the products.
     *
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array {
        $products = [];

        foreach ($this -> getCart() as $id => $quantity) {
            $product = $this -> productRepository -> find($id);

            if (!$product)
                continue;

            $products[] = new CartItem($product, $quantity);
        }

        return $products;
    }

    /**
     *
     * This function returns the number of items in the cart
     *
     * @return int
     */
    public function cartSize(): int {
        $size = 0;
        foreach ($this -> getCart() as $id => $qty)
            $size += $qty;

        return $size;
    }
}