<?php

    namespace App\Controller;

    use App\Entity\People;
    use App\Form\PeopleType;
    use App\Service\UploaderFileService;
    use App\Repository\PeopleRepository;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\{Request, Response};
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    #[Route('/people')]
    #[Security("is_granted('ROLE_USER')")]
    class PeopleController extends AbstractController
    {

        #[Route('/', name: 'app_people_index', methods: ['GET'])]
        public function index(PeopleRepository $peopleRepository, PaginatorInterface $paginatorInterface, Request $request) : Response
        {
            $people = $peopleRepository->findAll();
            $year = []; $count = [];
            foreach ($people as $value) {
                if (!in_array($value->getStartdate()->format('Y'), $year)) {
                    $year[] = $value->getStartdate()->format('Y');
                }
            }
            rsort($year);
            $length = count($year);
            for ($i=0; $i < $length; $i++) { 
                $k = 0; $l = count($people);
                for ($j=0; $j < $l; $j++) { 
                    if ($year[$i] == $people[$j]->getStartdate()->format("Y")) {
                        $k++; 
                    }
                }
                $count[$i] = $k;
            }
            
            for ($i=0; $i < $length ; $i++) { 
                $years [] = ["year" => $year[$i], "element" => $count[$i]];
            }
            $years = $paginatorInterface->paginate(
                $years,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('people/index.html.twig', [
                'years' => $years,
            ]);   
        }
        
        
        #[Route('/statistical/{year}', name: 'app_people_statistical', methods: ['GET'], requirements: ['year' => '\d+'])]
        public function statistical(int $year, PeopleRepository $peopleRepository, PaginatorInterface $paginatorInterface, Request $request): Response
        {
            $start = new \DateTime("$year-01-01");
            $end = new \DateTime("$year-12-01");
            $people = $peopleRepository->findByYears($start, $end);
            
            $people = $paginatorInterface->paginate(
                $people,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('people/statistical.html.twig', [
                'people' => $people,
                'year' => $year
            ]);
        }

        #[Route('/new', name: 'app_people_new', methods: ['GET', 'POST'])]
        public function new(Request $request, PeopleRepository $peopleRepository, UploaderFileService $uploaderFileService): Response
        {
            $person = new People();
            $form = $this->createForm(type: PeopleType::class, data: $person);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $datafile = [
                    'report' => $form->get('report')->getData(),
                    'otherfile' => $form->get('otherfile')->getData(),
                    'internshipletter' => $form->get('internshipletter')->getData(),
                ];

                foreach ($datafile as $key => $value) {
                    if ($value) {
                        $file = $form->get("$key")->getData();
                        if ($file) {
                            $fieldName = "set" . ucfirst($key);
                            $filename = $uploaderFileService->upload(file: $file, target: null);
                            $person->$fieldName($filename);
                        }
                    }
                }

                $now = new \DateTime();
                $interval = $now->diff($form->get('dateofbirth')->getData());
                $majority = (int) $interval->format('%y');
                
                if($form->get('startdate')->getData() >=  $form->get('enddate')->getData() || $form->get('dateofbirth')->getData() >= $form->get('startdate')->getData() || $majority < 15 ){
                    $this->addFlash(
                        'danger',
                        'The dates are inconsistent'
                    );

                    return $this->redirectToRoute('app_people_new', [], Response::HTTP_SEE_OTHER);
                }
                
                $peopleRepository->add($person, true);
                $this->addFlash(
                    'info',
                    'Intern register with success'
                );
                return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('people/new.html.twig', [
                'person' => $person,
                'form' => $form,
            ]);
        }

        #[Route('/show/{id}/{year}/{slug}', name: 'app_people_show', methods: ['GET'], requirements: ["slug" => "[a-z0-9\-]*", "year" =>"[0-9]*", "id" =>"[0-9]*"])]
        public function show(int $year, string $slug, People $person): Response
        {
            if ($person->getSlug() !== $slug) {
                return $this->redirectToRoute('app_people_show', [
                    'id' => $person->getId(),
                    'year' => $year,
                    'slug' => $person->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }
            return $this->render('people/show.html.twig', [
                'person' => $person,
                'year' => $year
            ]);
        }

        #[Route('/edit/{id}/{slug}', name: 'app_people_edit', methods: ['GET', 'POST'], requirements: ["slug" => "[a-z0-9\-]*", "year" =>"[0-9]*", "id" =>"[0-9]*"])]
        public function edit(Request $request, string $slug, People $person, PeopleRepository $peopleRepository): Response
        {
            if ($person->getSlug() !== $slug) {
                return $this->redirectToRoute('app_people_edit', [
                    'id' => $person->getId(),
                    'slug' => $person->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }

            $form = $this->createForm(PeopleType::class, $person);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $now = new \DateTime();
                $interval = $now->diff($form->get('dateofbirth')->getData());
                $majority = (int) $interval->format('%y');
                
                if($form->get('startdate')->getData() >=  $form->get('enddate')->getData() || $form->get('dateofbirth')->getData() >= $form->get('startdate')->getData() || $majority < 15 ){
                    $this->addFlash(
                        'danger',
                        'The dates are inconsistent'
                    );

                    return $this->redirectToRoute('app_people_edit', [
                        'id' => $person->getId(),
                        'slug' => $person->getSlug()
                    ], Response::HTTP_SEE_OTHER);
                }

                $peopleRepository->add($person, true);

                $this->addFlash(
                    'info',
                    'The intern has been successfully modified'
                );

                return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
            }

            
            return $this->renderForm('people/edit.html.twig', [
                'person' => $person,
                'form' => $form,
            ]);
        }

        #[Route('/delete/{id}', name: 'app_people_delete', methods: ['POST'], requirements: ["id" =>"[0-9]*"])]
        public function delete(Request $request, People $person, PeopleRepository $peopleRepository): Response
        {
            if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
                $peopleRepository->remove($person, true);
            }
            $this->addFlash('info', 'You have delete successfully');
            return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
        }
    }
