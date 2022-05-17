<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController {

    #[Route('/auth/login', name: 'app_auth_login', priority: 1)]
    public function login(AuthenticationUtils $utils, FormFactoryInterface $factory): Response {

        $form = $this -> createForm(LoginType::class, ['email' => $utils -> getLastUsername()]);

        dump($utils -> getLastAuthenticationError(), $utils -> getLastUsername());

        return $this->render('auth/login.html.twig', [
            'formView' => $form -> createView(),
            'error' => $utils -> getLastAuthenticationError(),
        ]);
    }

    #[Route('/auth/logout', name: 'app_auth_logout', priority: 1)]
    public function logout() {
        // Logout
    }
}
