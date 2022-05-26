<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER', message: "Vous devez être connecter pour payer votre commande!")]
class PurchasePaymentController extends AbstractController {

    protected PurchaseRepository $purchaseRepository;
    protected StripeService $stripeService;
    protected EntityManagerInterface $manager;
    protected CartService $cartService;

    public function __construct(PurchaseRepository $purchaseRepository, StripeService $stripeService, EntityManagerInterface $manager, CartService $cartService) {
        $this -> purchaseRepository = $purchaseRepository;
        $this -> stripeService = $stripeService;
        $this -> manager = $manager;
        $this -> cartService = $cartService;
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/purchase/pay/{id}', name: "app_purchase_payment", requirements: ['id' => "\d+"])]
    public function showCardForm($id): Response {

        $purchase = $this -> getPurchase($id);

        $intent = $this -> stripeService -> getPaymentIntent($purchase);

        $purchase -> setStripeIntentID($intent -> id);

        $this -> manager -> flush();

        return $this -> render('purchase/payment.html.twig', [
            'client_secret' => $intent -> client_secret,
            'id' => $purchase -> getId(),
            'stripePublicKey' => $this -> stripeService -> getPublicKey(),
            'purchase' => $purchase
        ]);
    }

    #[Route('/purchase/validate/{id}', name: "app_purchase_validate", requirements: ['id' => "\d+"])]
    public function validate(Request $request, $id): Response {

        $purchase = $this -> purchaseRepository -> find($id);

        if ($request -> query -> has('payment_intent') && $request -> query -> has('redirect_status')) {
            if ($request -> query -> get('payment_intent') === $purchase -> getStripeIntentID() && $request -> query -> get('redirect_status') === "succeeded") {
                $this -> cartService -> clearCart();

                $this -> addFlash('success', "Merci pour votre achat :) !");

                return $this -> redirectToRoute('app_home');
            }
        }

        return $this -> redirectToRoute('app_purchase_payment', [
            'id' => $id,
        ]);
    }

    private function getPurchase($id): Purchase|Response {
        $purchase = $this -> purchaseRepository -> find($id);

        if (empty($purchase)) {
            $this -> addFlash('warning', "La commande n'existe pas !");
            return $this -> redirectToRoute('app_purchase_list');
        }

        if (($purchase -> getUser() !== $this -> getUser()) || ($purchase -> getStatus() === Purchase::STATUS_PAID)) {
            $this -> addFlash('warning', "La commande n'existe pas !");
            return $this -> redirectToRoute('app_purchase_list');
        }

        return $purchase;
    }
}