<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Service\SendMailService;
    use App\Form\RegistrationFormType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    #[IsGranted(data:'ROLE_ADMIN', statusCode: Response::HTTP_FORBIDDEN)]
    class RegistrationController extends AbstractController
    {
        #[Route('/register', name: 'app_register')]
        public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SendMailService $sendMailService): Response
        {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $plainPassword = uniqid();
                // we define the role
                $user->setRoles(["USER_ROLE"]);
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                $sendMailService->send(from: 'contact@modernappfactory.com', to: $user->getEmail(), subject: "", template: "register", context: compact('user', 'plainPassword'));
                $this->addFlash(
                    'success',
                    'The account was created successfully.'
                );
                return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    }
