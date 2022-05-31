<?php

namespace App\Controller;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use App\Repository\ProductRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {

    #[Route('/{category_slug}/{product_slug}', name: "product_show", priority: -1)]
    public function show(ProductRepository $repository, EventDispatcherInterface $dispatcher, $product_slug): Response {

        $product = $repository -> findOneBy([
            'slug' => $product_slug,
        ]);

        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas.");
        }

        $dispatcher -> dispatch(new ProductViewEvent($product), Product::VIEW_EVENT);

        return $this -> render("product/show.html.twig", [
            'product' => $product
        ]);
    }
}
