<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class ProductViewSubscriber implements EventSubscriberInterface {

    protected LoggerInterface $logger;
    protected MailerInterface $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer) {
        $this -> logger = $logger;
        $this -> mailer = $mailer;
    }


    #[ArrayShape([Product::VIEW_EVENT => "string"])]
    public static function getSubscribedEvents(): array {
        return [
            Product::VIEW_EVENT => 'sendEmail'
        ];
    }

    #[NoReturn]
    public function sendEmail(ProductViewEvent $event) {
        $this -> logger -> info("Le produit ". $event -> getProduct() -> getName() ." (#". $event -> getProduct() -> getId() .") a été vu !");

        /*$email = new TemplatedEmail();
        $email
            -> from(new Address("no-reply@mail.com", "Info de la boutique"))
            -> to("admin@mail.com")
            -> subject("Visite de ". $event -> getProduct() -> getName() ." (#". $event -> getProduct() -> getId() .")!")
            -> htmlTemplate('email/product_view.html.twig')
            -> context([
                'product' => $event -> getProduct()
            ])
            -> text("Un visiteur est en train de visionner la page du produit ". $event -> getProduct() -> getName() ." (#". $event -> getProduct() -> getId() .")!")
        ;

        $this -> mailer -> send($email);*/
    }
}