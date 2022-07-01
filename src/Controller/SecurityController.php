<?php

    namespace App\Controller;

    use App\Form\ResetPasswordType;
    use App\Service\SendMailService;
    use App\Repository\UserRepository;
    use App\Form\ResetPasswordRequestType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\{Request, Response};
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


    class SecurityController extends AbstractController
    {
        #[Route(path: '/login', name: 'app_login')]
        public function login(AuthenticationUtils $authenticationUtils): Response
        {
            if ($this->getUser()) {
                if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->redirectToRoute('app_home');
                } else {
                    $this->addFlash('success', "Welcome dear " . $this->getUser()->getFullname() . ", we wish you a good user experience !");
                    return $this->redirectToRoute('app_people_index');
                }
            }

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }

        #[Route(path: '/logout', name: 'app_logout')]
        public function logout(): void
        {
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        }

        #[Route(path: "/forgotten/password", name: "app_forgotten_password")]
        public function forgottenPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $entityManagerInterface, SendMailService $sendMailService) : Response
        {
            $form = $this->createForm(ResetPasswordRequestType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->findOneByEmail($form->get('email')->getData());
                if ($user) {
                    $token = $tokenGeneratorInterface->generateToken();
                    $user->setResetToken($token);
                    
                    try {
                        $entityManagerInterface->persist($user);
                        $entityManagerInterface->flush();
                    } catch (\Throwable $th) {
                        $this->addFlash('danger', 'There was a problem. Message :' . $th->getMessage());
                        return $this->redirectToRoute(route: 'app_forgotten_password', parameters: [], status: Response::HTTP_SEE_OTHER);
                    }

                    $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                    $context = compact("url", "user");
                    $sendMailService->send(from: "contact@modernappfactory.com", to: $user->getEmail(), subject: "Password reset", template: "Password_reset", context: $context);
                    
                    $this->addFlash(
                        'info',
                        'Request well received. Please check your email inbox for verification of the password reset link. Wait 20 seconds or check your spam folder.'
                    );
                    return $this->redirectToRoute(route: 'app_forgotten_password', parameters: [], status: Response::HTTP_SEE_OTHER);
                }
                
                $this->addFlash('danger', 'There was a problem.');
                return $this->redirectToRoute(route: 'app_forgotten_password', status: Response::HTTP_SEE_OTHER);
            }

            return $this->render('security/reset_password_request.html.twig', ['resetPasswordRequestForm' => $form->createView()]);
        }

        #[Route(path: "/forgotten/password/{token}", name: "app_reset_password")]
        public function resetPassword(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface) : Response
        {
            $user = $userRepository->findOneBy(["reset_token" => $token]);

            if ($user) {
                $form = $this->createForm(ResetPasswordType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setResetToken('')
                        ->setPassword(
                            $userPasswordHasherInterface->hashPassword(
                                $user,
                                $form->get('plainPassword')->getData()
                            )
                        )
                    ;

                    $entityManagerInterface->persist($user);
                    $entityManagerInterface->flush();

                    $this->addFlash(
                        'success',
                        'Password has ben change as successfuly.'
                    );

                    return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                }

                $this->addFlash(
                    'info',
                    'Your email has been verified, please create a new password..'
                );

                return $this->render('security/reset_password.html.twig',['resetPasswordForm' => $form->createView()]);
            }

            $this->addFlash('danger', 'There was a problem. Invalid Token');
            return $this->redirectToRoute(route: 'app_forgotten_password', status: Response::HTTP_SEE_OTHER);
        }
    }
