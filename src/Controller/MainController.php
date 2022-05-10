<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {

    #[Route("/", name: "app_main")]
    public function index(): Response {

        return $this -> render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route("/test/{age<\d+>?0}", name: "app_test", methods: ["GET"])]
    public function test(Request $request, $age) {

        dd($age);

//        return $this -> render('main/index.html.twig', [
//            'controller_name' => 'MainController',
//        ]);
    }
}
