<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {

    #[Route('/{slug}', name: 'product_category')]
    public function category(CategoryRepository $categoryRepository , $slug): Response {

        $category = $categoryRepository -> findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw new NotFoundHttpException("La catégorie demandée n'existe pas.");
        }

        return $this->render("category/show.html.twig", [
            'category' => $category,
        ]);
    }

    #[Route('/{category_slug}/{product_slug}', name: "product_show")]
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
