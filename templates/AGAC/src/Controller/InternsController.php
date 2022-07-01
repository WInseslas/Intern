<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternsController extends AbstractController
{
    /**
     * @Route("/interns", name="app_interns")
     */
    public function index(): Response
    {
        $current_menu = "app_interns";
        return $this->render('interns/index.html.twig', compact("current_menu"));
    }
}
