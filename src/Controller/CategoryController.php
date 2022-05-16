<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {

    #[Route('{slug}', name: 'app_category_show')]
    public function showCategory(CategoryRepository $categoryRepository, ProductRepository $productRepository, $slug): Response {

        $category = $categoryRepository -> findOneBy([
            'slug' => $slug,
        ]);

        if (!$category)
            throw new NotFoundHttpException("La category demandé est introuvable!");

        $products = $productRepository -> findBy([
            'category' => $category -> getId(),
        ]);

        return $this -> render('category/show.html.twig', [
            'products' => $products,
            'category' => $category,
        ]);
    }
}
