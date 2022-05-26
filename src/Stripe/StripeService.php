<?php

namespace App\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService {

    protected const CURRENCY = "eur";

    protected string $SECRET_KEY;
    protected string $PUBLIC_KEY;

    public function __construct(string $SECRET_KEY, string $PUBLIC_KEY) {
        $this -> SECRET_KEY = $SECRET_KEY;
        $this -> PUBLIC_KEY = $PUBLIC_KEY;
    }

    /**
     * @throws ApiErrorException
     */
    public function getPaymentIntent($purchase): PaymentIntent {
        Stripe::setApiKey($this -> SECRET_KEY);

        return PaymentIntent::create([
            'amount' => $purchase -> getTotal(),
            'currency' => self::CURRENCY,
        ]);
    }

    public function getPublicKey(): string {
        return $this -> PUBLIC_KEY;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string {
        return $this -> SECRET_KEY;
    }

}