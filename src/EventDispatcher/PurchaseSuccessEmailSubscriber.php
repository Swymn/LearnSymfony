<?php

namespace App\EventDispatcher;

use App\Entity\Purchase;
use App\Event\PurchaseSuccessEvent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger) {
        $this -> logger = $logger;
    }


    #[ArrayShape([Purchase::SUCCESS => "string"])]
    public static function getSubscribedEvents(): array {
        return [
            Purchase::SUCCESS => 'sendSuccessEmail'
        ];
    }

    #[NoReturn]
    public function sendSuccessEmail(PurchaseSuccessEvent $event) {
        $this -> logger -> info("Email envoyé à ". $event -> getPurchase() -> getUser() -> getEmail() . " pour la commande N°#" . $event -> getPurchase() -> getId());
    }

}