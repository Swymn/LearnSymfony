<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Utils\ProductUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response {

        $products = $productRepository -> findBy([], [], 3);

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/search', name: "app_home_search", priority: 2)]
    public function search(ProductUtils $utils, Request $request): Response {

        $value = $request -> query -> get('value');

        $products = $utils -> getProductByName($value);

        return $this -> render('home/search.html.twig', [
            'products' => $products,
        ]);
    }
}
