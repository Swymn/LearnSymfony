<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Purchase\PurchaseService;
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
    protected PurchaseService $purchaseService;

    public function __construct(CartService $cartService, EntityManagerInterface $manager, PurchaseService $purchaseService) {
        $this -> cartService = $cartService;
        $this -> manager = $manager;
        $this -> purchaseService = $purchaseService;
    }


    #[Route('/cart/coordinates', name: "app_cart_coordinates")]
    public function confirmation(Request $request): Response {

        $form = $this -> createForm(CartConfirmationType::class);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            if (empty($this -> cartService -> getDetailedCartItems())) {
                $this -> addFlash(CartService::DANGER, "Vous ne pouvez pas valider un panier vide!");
                return $this -> redirectToRoute('app_cart');
            }

            $purchase = $form -> getData();

            $this -> purchaseService -> storePurchase($purchase);

            return $this -> redirectToRoute('app_purchase_payment', [
                'id' => $purchase -> getId()
            ]);
        }

        return $this -> render('cart/coordinates.html.twig', [
            'form' => $form -> createView()
        ]);
    }
}