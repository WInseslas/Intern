<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Form\{ProfileType, ChangePasswordFormType};
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    #[Security("is_granted('ROLE_USER')")]
    class ProfileController extends AbstractController
    {
        #[Route('/profile/{id}/{slug}', name: 'app_profile', requirements: ["id" =>"[0-9]*"])]
        public function index(User $user, string $slug, int $id, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository): Response
        {
            if ($slug !== $user->getSlug() || $id !== $this->getUser()->getId()) {
                return $this->redirectToRoute('app_profile', ['id' => $this->getUser()->getId(), 'slug' => $this->getUser()->getSlug()], Response::HTTP_SEE_OTHER);
            }

            $password = $user->getPassword();
            $form = $this->createForm(type: ProfileType::class, data: $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($password);
                if ($userPasswordHasherInterface->isPasswordValid($user, $form->get('password')->getData())) {
                    $userRepository->add($user, true);
                    $this->addFlash(
                        'success',
                        'Your changes have been successfully saved'
                    );
                    return $this->redirectToRoute('app_home');
                } 
                else {
                    $this->addFlash(
                        'warning',
                        'Please your password is incorrect'
                    );
                    return $this->redirectToRoute('app_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                }
            }
            return $this->render('profile/index.html.twig', [
                'user' => $this->getUser(),
                'form' => $form->createView(),
            ]);
        }
         
        #[Route("/change/password/{id}/{slug}", name: "app_change_password")]
        public function changePassword(User $user, string $slug, int $id, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository): Response
        {
            if ($slug !== $user->getSlug() || $id !== $this->getUser()->getId()) {
                return $this->redirectToRoute('app_change_password', ['id' => $this->getUser()->getId(), 'slug' => $this->getUser()->getSlug()], Response::HTTP_SEE_OTHER);
            }
            $password = $user->getPassword();
            $form = $this->createForm(type: ChangePasswordFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($password);
                if ($userPasswordHasherInterface->isPasswordValid($user, $form->get('password')->getData())) {
                    if ($userPasswordHasherInterface->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                        $this->addFlash(
                            'warning',
                            'Your new password must be different from the old one'
                        );
                    } else {
                        $user->setPassword(
                                $userPasswordHasherInterface->hashPassword(
                                    $user,
                                    $form->get('plainPassword')->getData()
                                )
                            )
                            ->setupdatedAt(new \DateTimeImmutable())
                        ;
                        $userRepository->add($user, true);
                        $this->addFlash(
                            'success',
                            'Your password is up to date!'
                        );
                        return $this->redirectToRoute(route: 'app_login', status: Response::HTTP_SEE_OTHER);
                        
                    }
                } else {
                    $this->addFlash(
                        'warning',
                        'Your current password is incorrect'
                    );
                    return $this->redirectToRoute('app_change_password', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                }
                
            }
            return $this->render('profile/changePassword.html.twig', [
                'user' => $this->getUser(),
                'form' => $form->createView(),
            ]);
        }

        #[Route("/activity/log/{id}/{slug}", name: "app_activity_log")]
        public function activityLog(User $user, string $slug, int $id): Response
        {
            if ($slug !== $user->getSlug() || $id !== $this->getUser()->getId()) {
                return $this->redirectToRoute('app_activity_log', ['id' => $this->getUser()->getId(), 'slug' => $this->getUser()->getSlug()], Response::HTTP_SEE_OTHER);
            }
            return $this->render('profile/activityLog.html.twig', ['user' => $user]);
        }
    }
