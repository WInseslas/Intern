<?php

namespace App\Controller;

use App\Repository\{UsersRepository, OtherInformationsRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\{TextareaType, DateType, TextType};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    
    /**
     * users_repository
     *
     * @var UsersRepository
     */
    private $users_repository;
    
    /**
     * other_informations_repository
     *
     * @var OtherInformationsRepository
     */
    private $other_informations_repository;
    
    /**
     * __construct
     *
     * @param UsersRepository $users_repository
     * @return void
     */
    public function __construct(UsersRepository $users_repository, OtherInformationsRepository $other_informations_repository)
    {
        $this->users_repository = $users_repository;
        $this->other_informations_repository = $other_informations_repository;
    }

    /**
     * @Route("/users", name="users.index")
     */
    public function index(): Response
    {
        $users = $this->users_repository->findAllInters('intern');
        dd($users);
                
        return $this->render('users/index.html.twig', [
            'current_menu' => '/users',
            'users' => $users
        ]);
    }


    /**
     * @Route("/users/{slug}-{id}", name="users.show", requirements={"slug": "[a-z0-9\-]*",  "id": "[0-9]*"})
     */
    public function show(int $id, string $slug): Response
    {
        $user = $this->users_repository->find($id);

        dd($user);
        if ($user->getSlug() !== $slug) {
            return $this->redirectToRoute('users.show', [
                'id' => $user->getId(),
                'slug' => $user->getSlug()
            ], 301);
        }
        return $this->render('users/show.html.twig', [
            'current_menu' => '/users',
            'user' => $user
        ]);
    }

    /**
     * @Route("/users/{slug}/{id}", name="users.edit", requirements={"slug": "[a-z0-9\-]*",  "id": "[0-9]*"})
     */
    public function edit(int $id, string $slug): Response
    {
        $user = $this->users_repository->find($id);
        $otherinformation = $this->other_informations_repository->find($user->getOtherInformations()->getId());

        if ($user->getSlug() !== $slug) {
            return $this->redirectToRoute('users.show', [
                'id' => $user->getId(),
                'slug' => $user->getSlug()
            ], 301);
        }

        dump($user);
        

        $form = $this->createFormBuilder()
                    ->add('last_name', TextType::class, ['attr' => ['placeholder' => 'Nom'],
                                                        'label' => 'Nom',
                                                        'required' => false,
                                                        'empty_data' => 'qmdqwm' 
                                                    ]
                    )
                    ->add('first_name', TextType::class, ['attr' => ['placeholder' => 'Prénom'],
                                                        'label' => 'Prénom',
                                                        'required' => false  
                                                    ]
                    )
                    ->add('date_of_birth', DateType::class, ['label' => 'Date de naissance',] 
                    )
                    ->add('post', null, ['label' => 'Poste',])
                    ->add('topic', TextareaType::class, ['label' => 'Theme'])
                    ->add('start_date', DateType::class, ['label' => 'Début',])
                    ->add('end_date', DateType::class, ['label' => 'Fin',])
                    ->getForm();
        ;
        return $this->render('users/edit.html.twig', [
            'current_menu' => '/users',
            'form' => $form->createView(),
            'user' => $user,
            'otherinformation' => $otherinformation
        ]);
    }
}
