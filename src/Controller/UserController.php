<?php
    namespace App\Controller;

    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    #[Route('/user')]
    #[IsGranted(data:'ROLE_ADMIN', statusCode: Response::HTTP_FORBIDDEN)]
    class UserController extends AbstractController
    {
        #[Route('/', name: 'app_user_index', methods: ['GET'])]
        public function index(UserRepository $userRepository): Response
        {
            $users = $userRepository->findAllByName($this->getUser()->getId());
            return $this->render('user/index.html.twig', [
                'users' => $users,
            ]);
        }

        #[Route('/show/{id}/{slug}', name: 'app_user_show', methods: ['GET'])]
        public function show(User $user, String $slug): Response
        {
            if ($user->getSlug() !== $slug) {
                return $this->redirectToRoute('app_user_show', [
                    'id' => $user->getId(),
                    'slug' => $user->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }

        #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
        public function delete(Request $request, User $user, UserRepository $userRepository): Response
        {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user, true);
            }
            $this->addFlash(
                'success',
                'Your account has been successfully deleted'
            );
            return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
        }
    }
