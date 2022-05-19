<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem {

    protected Product $product;
    protected int $quantity;

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function __construct(Product $product, int $quantity) {
        $this -> product = $product;
        $this -> quantity = $quantity;
    }

    /**
     * This function allows you to calculate the total cost of an item when there are several items.
     * @return int
     */
    public function getTotal() : int {
        return $this -> product -> getPrice() * $this -> quantity;
    }

    public function getProduct(): Product {
        return $this -> product;
    }

    public function getQuantity(): int {
        return $this -> quantity;
    }




}