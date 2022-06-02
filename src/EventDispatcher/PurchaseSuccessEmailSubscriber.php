<?php

namespace App\EventDispatcher;

use App\Entity\Purchase;
use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface {

    protected LoggerInterface $logger;
    protected MailerInterface $mailer;
    protected Security $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security) {
        $this -> logger = $logger;
        $this -> mailer = $mailer;
        $this -> security = $security;
    }

    #[ArrayShape([Purchase::SUCCESS => "string"])]
    public static function getSubscribedEvents(): array {
        return [
            Purchase::SUCCESS => 'sendSuccessEmail'
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[NoReturn]
    public function sendSuccessEmail(PurchaseSuccessEvent $event) {
        # $this -> logger -> info("Email envoyé à ". $event -> getPurchase() -> getUser() -> getEmail() . " pour la commande N°#" . $event -> getPurchase() -> getId());

        // 1. Récupérer l'utilisateur actuellement en ligne (Pour son email).
        /** @var User $currentUser */
        $currentUser = $this -> security -> getUser();

        // 2. Récupérer la commande.
        $purchase = $event -> getPurchase();

        // 3. Écrire l'email.
        $email = new TemplatedEmail();
        $email
            -> to(new Address($currentUser -> getEmail(), $currentUser -> getFullName()))
            -> from("contact@gmail.com")
            -> subject("Votre commande (#{$purchase -> getId()}) a bien été enregistrer!")
            -> htmlTemplate('email/purchase_success.html.twig')
            -> context([
                'purchase' => $purchase,
                'user' => $currentUser
            ])
        ;

        // 4. Envoyez l'email.
        $this -> mailer -> send($email);

    }

}