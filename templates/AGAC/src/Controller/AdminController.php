<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    
    /**
     * @var NobodyRepository $nobodyRepository
     */
    private $nobodyRepository;
    
    // public function __construct(NobodyRepository $nobodyRepository)
    // {
    //     $this->nobodyRepository = $nobodyRepository;
    // }

    /**
     * @Route("/admin", name="admin.index")
     */
    public function index(UserRepository $userRepository): Response
    {
        $userRepository = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'current_menu' => '/admin', 
            'nobody' => $userRepository
        ]);
    }

    /**
     * @Route("/admin/{slug}-{id}", name="admin.show", requirements={"slug": "[a-z0-9\-]*",  "id": "[0-9]*"})
     */
    public function show(int $id, string $slug): Response
    {
        // $user = $this->users_repository->find($id);

        // dd($user);
        // if ($user->getSlug() !== $slug) {
        //     return $this->redirectToRoute('users.show', [
        //         'id' => $user->getId(),
        //         'slug' => $user->getSlug()
        //     ], 301);
        // }
        return $this->render('Admin/show.html.twig', [
            'current_menu' => '/users'
        ]);
    }
}
