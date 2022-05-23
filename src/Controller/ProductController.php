<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {

    #[Route('/{category_slug}/{product_slug}', name: "product_show", priority: -1)]
    public function show(ProductRepository $repository, $product_slug): Response {

        $product = $repository -> findOneBy([
            'slug' => $product_slug,
        ]);

        if (!$product) {
            throw new NotFoundHttpException("Le produit demandé n'existe pas.");
        }

        return $this -> render("product/show.html.twig", [
            'product' => $product
        ]);
    }
}
