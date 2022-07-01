<?php

    namespace App\Controller;

    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\{Response, Request};
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Repository\{PeopleRepository, CertificateRepository, UserRepository};


    class HomeController extends AbstractController
    {
        #[Route('/home', name: 'app_home')]
        #[Security("is_granted('ROLE_ADMIN')")]
        public function index(PeopleRepository $peopleRepository, UserRepository $userRepository, CertificateRepository $certificateRepository): Response
        {
            if(!$this->getUser()){
                $this->addFlash(type:'warning', message: "Please");
                return $this->redirectToRoute(route: 'app_login', status: Response::HTTP_SEE_OTHER);
            }
            $interns = count($peopleRepository->findAll());
            $certificateEmployee = count($certificateRepository->findByType(1));
            $certificateInternship = count($certificateRepository->findByType(0));
            $users = $userRepository->findAllByName($this->getUser()->getId());
            $verification = count($certificateRepository->findBy(['isverified' => true]));
            return $this->render('home/index.html.twig', [
                'users' => $users,
                'interns' => $interns,
                'verification' => $verification,
                'certificateEmployee' => $certificateEmployee,
                'certificateInternship' => $certificateInternship,
            ]);
        }

        #[Security("is_granted('ROLE_USER')")]
        #[Route("/search", name: "app_search")]
        public function Search(PeopleRepository $peopleRepository, PaginatorInterface $paginatorInterface, Request $request): Response
        {
            if (!empty($_POST['Search'])) {
                $search = $_POST['Search'];
                $result = $peopleRepository->search($search);
            } else {
                $this->addFlash('warning', 'The server cannot or will not process the request');
                return $this->redirectToRoute('app_people_index');
            }

            $result = $paginatorInterface->paginate(
                $result,
                $request->query->getInt('page', 1),
                12
            );

            return $this->render('home/search.html.twig', [
                'results' => $result,
                'search' => $search,
            ]);
        }
    }
