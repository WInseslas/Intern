<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/authentication_login", name="authentication.login")
     */
    public function login(): Response
    {
        return $this->render('authentication/login.html.twig', [
            'controller_name' => 'AuthenticationController',
            'current_menu' => '/authentication-login'
        ]);
    }

    /**
     * @Route("/authentication-register", name="authentication.register")
     */
    public function register(): Response
    {
        return $this->render('authentication/register.html.twig', [
            'controller_name' => 'AuthenticationController',
            'current_menu' => '/authentication-register'
        ]);
    }
}
