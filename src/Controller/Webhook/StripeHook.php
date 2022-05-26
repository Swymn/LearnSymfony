<?php

namespace App\Controller\Webhook;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeHook extends AbstractController {

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

    #[Route('/webhook/stripe', name: "webhooks")]
    public function webhooks(): JsonResponse {

        $payload = file_get_contents('php://input');
        $event = json_decode($payload);

        Stripe::setApiKey($this -> stripeService -> getSecretKey());

        $type = $event -> type;
        $object = $event -> data -> object;

        switch ($type) {
            case 'payment_intent.succeeded':
                $this -> handlePaymentIntentSucceeded($object);
                break;
            default:
                // Unexpected event type
                error_log('Received unknown event type');
        }

        return new JsonResponse(['status' => 'success']);
    }

    private function handlePaymentIntentSucceeded(mixed $paymentIntent) {
        $purchase = $this -> purchaseRepository -> findOneBy([
            'StripeIntentID' => $paymentIntent -> id,
        ]);

        if ($purchase) {
            $purchase -> setStatus(Purchase::STATUS_PAID);
            $this -> manager -> flush();
        }
    }
}