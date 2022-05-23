<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER', message: "Vous devez être connecté pour continuer votre commande !")]
class PurchaseConfirmationController extends AbstractController {

    protected CartService $cartService;
    protected EntityManagerInterface $manager;

    public function __construct(CartService $cartService, EntityManagerInterface $manager) {
        $this -> cartService = $cartService;
        $this -> manager = $manager;
    }

    #[Route('/cart/coordinates', name: "app_cart_coordinates")]
    public function confirmation(Request $request): Response {

        $form = $this -> createForm(CartConfirmationType::class);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            $user = $this -> getUser();
            $cartItems = $this -> cartService -> getDetailedCartItems();

            if (empty($cartItems)) {
                $this -> addFlash(CartService::DANGER, "Vous ne pouvez pas valider un panier vide!");
                return $this -> redirectToRoute('app_cart');
            }

            $purchase = $form -> getData();

            $purchase
                -> setUser($user)
                -> setPurchasedAt(new \DateTime())
                -> setTotal($this -> cartService -> getTotal())
            ;

            $this -> manager -> persist($purchase);

            foreach ($cartItems as $cartItem) {
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

            $this -> cartService -> clearCart();

            $this -> addFlash(CartService::SUCCESS, "La commande a bien été enregistrer!");
            return $this -> redirectToRoute('app_cart');
        }

        return $this -> render('cart/coordinates.html.twig', [
            'form' => $form -> createView()
        ]);
    }

}