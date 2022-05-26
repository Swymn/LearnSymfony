<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseService {

    protected CartService $cartService;
    protected EntityManagerInterface $manager;
    protected Security $security;

    public function __construct(CartService $cartService, EntityManagerInterface $manager, Security $security) {
        $this -> cartService = $cartService;
        $this -> manager = $manager;
        $this -> security = $security;
    }


    public function storePurchase(Purchase $purchase): void {

        $purchase
            -> setUser($this -> security -> getUser())
            -> setPurchasedAt(new \DateTime())
            -> setTotal($this -> cartService -> getTotal())
        ;

        $this -> manager -> persist($purchase);

        foreach ($this -> cartService -> getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem
                -> setPurchase($purchase)
                -> setProduct($cartItem -> getProduct())
                -> setProductName($cartItem -> getProduct() -> getName())
                -> setProductPrice($cartItem -> getProduct() -> getPrice())
                -> setQuantity($cartItem -> getQuantity())
                -> setTotal($cartItem -> getTotal())
            ;

            $this -> manager -> persist($purchaseItem);
        }

        $this -> manager -> flush();
    }

}