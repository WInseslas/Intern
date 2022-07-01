<?php
    namespace App\Controller;

    use App\Entity\Certificate;
    use App\Service\GenerationPdfService;
    use Symfony\Component\Form\FormInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Knp\Component\Pager\Pagination\PaginationInterface;
    use Symfony\Component\HttpFoundation\{Response, Request};
    use App\Repository\{PeopleRepository, CertificateRepository};
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Form\{CertificateType, CertificateWorkType, CertificateSearchType};

    #[Route('/certificate')]
    class CertificateController extends AbstractController
    {
        #[Security("is_granted('ROLE_USER')")]
        #[Route('/internship', name: 'app_certificate_index', methods: ['GET'])]
        public function index(CertificateRepository $certificateRepository, PaginatorInterface $paginatorInterface, Request $request): Response
        {
            $certificates = $this->communIndex(certificateRepository: $certificateRepository, paginatorInterface: $paginatorInterface, request: $request, post: 0);
            return $this->render('certificate/index.html.twig', [
                'certificates' => $certificates,
                'route' => "app_certificate_new",
            ]);
        }

        #[Security("is_granted('ROLE_USER')")]
        #[Route('/new/internship/', name: 'app_certificate_new', methods: ['GET', 'POST'])]
        public function new(Request $request, CertificateRepository $certificateRepository, PeopleRepository $peopleRepository): Response
        {
            $params = 'trainee';

            $form = $this->createForm(type: CertificateType::class);
            $form->handleRequest($request);
            
            $redirect = $this->communNew(form: $form, peopleRepository: $peopleRepository, certificateRepository: $certificateRepository, params: $params);
            if ($redirect === 0) {
                return $this->redirectToRoute('app_certificate_new', [], Response::HTTP_SEE_OTHER);
            } elseif ($redirect === 1) {
                return $this->redirectToRoute('app_certificate_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('certificate/new.html.twig', [
                'form' => $form,
                'message' => "Create a certificate of completion of internship",
                'route' => "app_certificate_index"
            ]);
        }

        #[Route('/show/{id}/{slug}', name: 'app_certificate_show', methods: ['GET'])]
        public function show(Certificate $certificate, String $slug, GenerationPdfService $generationPdfService ): Response
        {
            if ($certificate->getSlug() !== $slug) {
                return $this->redirectToRoute('app_certificate_show', [
                    'id' => $certificate->getId(),
                    'slug' => $certificate->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }
            $generationPdfService->geration($certificate);
        }

        #[Security("is_granted('ROLE_ADMIN')")]
        #[Route('/work', name: 'app_certificate_index_work', methods: ['GET'])]
        public function indexW(CertificateRepository $certificateRepository, PaginatorInterface $paginatorInterface, Request $request): Response
        {
            $certificates = $this->communIndex(certificateRepository: $certificateRepository, paginatorInterface: $paginatorInterface, request: $request, post: 1);
            return $this->render('certificate/index.html.twig', [
                'certificates' => $certificates,
                'route' => "app_certificate_new_work",
            ]);
        }

        #[Security("is_granted('ROLE_ADMIN')")]
        #[Route('/new/work/', name: 'app_certificate_new_work', methods: ['GET', 'POST'])]
        public function newW(Request $request, CertificateRepository $certificateRepository, PeopleRepository $peopleRepository): Response
        {
            $params = 'employee';
            $form = $this->createForm(type: CertificateWorkType::class);
            $form->handleRequest($request);
            $redirect = $this->communNew(form: $form, peopleRepository: $peopleRepository, certificateRepository: $certificateRepository, params: $params);
            if ($redirect === 0) {
                return $this->redirectToRoute('app_certificate_new_work', [], Response::HTTP_SEE_OTHER);
            } elseif ($redirect === 1) {
                return $this->redirectToRoute('app_certificate_index_work', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('certificate/new.html.twig', [
                'form' => $form,
                'message' => "Create a work certificate",
                'route' => "app_certificate_index_work"
            ]);
        }

        #[Security("is_granted('ROLE_USER')")]
        #[Route('/{id}', name: 'app_certificate_delete', methods: ['POST'])]
        public function delete(Request $request, Certificate $certificate, CertificateRepository $certificateRepository): Response
        {
            if ($this->isCsrfTokenValid('delete'.$certificate->getId(), $request->request->get('_token'))) {
                $certificateRepository->remove($certificate, true);
            }
            $this->addFlash(
                'info',
                'You have delete successfully !'
            );
            return $this->redirectToRoute('app_certificate_index', [], Response::HTTP_SEE_OTHER);
        }
        
        #[Route('/search/', name: 'app_certificate_search', methods: ['GET', 'POST'])]
        public function search(Request $request, CertificateRepository $certificateRepository) : Response
        {
            $form = $this->createForm(type: CertificateSearchType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $certificate = $certificateRepository->findOneBy(['coded' => $form->get('coded')->getData()]);
                if (!$certificate) {
                    $this->addFlash('danger', 'No certificate has this code');
                    return $this->redirectToRoute('app_certificate_search', [], Response::HTTP_SEE_OTHER);
                } else {
                    $certificate->setIsverified(true);
                    $certificateRepository->add($certificate, true);
                    return $this->redirectToRoute('app_certificate_show', [
                        'id' => $certificate->getId(),
                        'slug' => $certificate->getSlug()
                    ], Response::HTTP_SEE_OTHER);
                }
            }
            return $this->render('certificate/search.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        

        /**
         * This
         *
         * @param  FormInterface $form
         * @param  PeopleRepository $peopleRepository
         * @param  CertificateRepository $certificateRepository
         * @param  String $params
         * @return void
         */
        private function communNew(FormInterface $form, PeopleRepository $peopleRepository, CertificateRepository $certificateRepository, string $params)
        {
            if ($form->isSubmitted() && $form->isValid()) {
                $peopleId = $form->get('people')->getData();

                foreach ($peopleId as $value) {
                    $people = $peopleRepository->findOneById($value);
                    if (!is_object($people)) {
                        $this->addFlash(
                            'danger',
                            'You have entered a non-existent ' . $params . ' !'
                        );
                        return 0;
                    }
                    $peoples[] = $people;
                }
                $nobodys = count($peoples);

                for ($i = 0; $nobodys > $i; $i++) { 
                    $code = substr(uniqid(), 0, 8);
                    $certificate = new Certificate();
                    $certificate->setCoded($code);
                    $certificate->setTemplate($form->get('template')->getData());
                    $certificate->setPeople($peoples[$i]);
                    $certificate->setUser($this->getUser());
                    $certificates[] = $certificate;
                }

                foreach ($certificates as $certificate){
                    $certificateRepository->add($certificate, true);
                }

                $this->addFlash(
                    "success",
                    "You have successfully certified " . $nobodys . " " . $params ." !"
                );
                return 1;
            }
        }
        
        /**
         * This
         *
         * @param  CertificateRepository $certificateRepository
         * @param  PaginatorInterface $paginatorInterface
         * @param  Request $request
         * @param  int $post
         * @return PaginationInterface
         */
        private function communIndex(CertificateRepository $certificateRepository, PaginatorInterface $paginatorInterface, Request $request, int $post) : PaginationInterface
        {
            return $certificates = $paginatorInterface->paginate(
                $certificateRepository->findByType(post: $post),
                $request->query->getInt('page', 1),
                10
            );
        }
    }
