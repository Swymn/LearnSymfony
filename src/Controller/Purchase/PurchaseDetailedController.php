<?php

namespace App\Controller\Purchase;

use App\Repository\PurchaseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER', message: "Vous devez être connecté pour accéder à vos commandes !")]
class PurchaseDetailedController extends AbstractController {

    #[Route('/purchase/{id}', name: 'app_purchase_show', requirements: ['id' => "\d+"])]
    public function purchase(PurchaseRepository $repository, $id): Response {

        $purchase = $repository -> find($id);
        $user = $this -> getUser();

        if (empty($purchase) || $purchase -> getUser() !== $user)
            throw $this -> createNotFoundException("Cette commande n'existe pas !");

        return $this -> render("purchase/detailed.html.twig", [
            'purchase' => $purchase,
        ]);
    }

    #[Route('/purchase/{id}/products', name: 'app_purchase_show_product', requirements: ['id' => "\d+"])]
    public function purchaseProduct(PurchaseRepository $repository, $id): Response {

        $purchase = $repository -> find($id);
        $user = $this -> getUser();

        if (empty($purchase) || $purchase -> getUser() !== $user)
            throw $this -> createNotFoundException("Cette commande n'existe pas !");

        return $this -> render("purchase/detailedProduct.html.twig", [
            'purchase' => $purchase,
        ]);
    }

}