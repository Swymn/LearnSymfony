<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface {

    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger) {
        $this -> logger = $logger;
    }


    #[ArrayShape([Product::VIEW_EVENT => "string"])]
    public static function getSubscribedEvents(): array {
        return [
            Product::VIEW_EVENT => 'sendNotif'
        ];
    }

    #[NoReturn]
    public function sendNotif(ProductViewEvent $event) {
        $this -> logger -> info("Le produit ". $event -> getProduct() -> getName() ." (#". $event -> getProduct() -> getId() .") a été vu !");
    }
}