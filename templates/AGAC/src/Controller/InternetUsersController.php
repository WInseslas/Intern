<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternetUsersController extends AbstractController
{
    /**
     * @Route("/internet/users", name="app_internet_users")
     */
    public function index(): Response
    {
        return $this->render('internet_users/index.html.twig', [
            'controller_name' => 'InternetUsersController',
        ]);
    }
}
