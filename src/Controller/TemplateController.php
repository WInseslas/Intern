<?php

    namespace App\Controller;

    use App\Entity\Template;
    use App\Form\TemplateType;
    use App\Service\UploaderFileService;
    use App\Repository\TemplateRepository;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    #[Route('/template')]
    #[Security("is_granted('ROLE_USER')")]
    class TemplateController extends AbstractController
    {
        #[Route('/', name: 'app_template_index', methods: ['GET'])]
        public function index(TemplateRepository $templateRepository, PaginatorInterface $paginatorInterface, Request $request): Response
        {
            $templates = $templateRepository->findAll();
            $templates = $paginatorInterface->paginate(
                $templates,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('template/index.html.twig', [
                'templates' => $templates,
            ]);
        }

        #[Route('/new', name: 'app_template_new', methods: ['GET', 'POST'])]
        public function new(Request $request, TemplateRepository $templateRepository, UploaderFileService $uploaderFileService): Response
        {
            $template = new Template();
            $form = $this->createForm(TemplateType::class, $template);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $template->setAuthor($this->getUser());
                $filename = $uploaderFileService->upload(file: $form->get('file')->getData(), target: "target");
                $template->setFile($filename);
                $templateRepository->add($template, true);
                $this->addFlash(
                    'info',
                    'Template register with success'
                );
                return $this->redirectToRoute('app_template_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('template/new.html.twig', [
                'template' => $template,
                'form' => $form,
            ]);
        }

        #[Route('/show/{id}/{slug}', name: 'app_template_show', methods: ['GET'])]
        public function show(Template $template, String $slug): Response
        {
            if ($template->getSlug() !== $slug) {
                return $this->redirectToRoute('app_template_show', [
                    'id' => $template->getId(),
                    'slug' => $template->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->render('template/show.html.twig', [
                'template' => $template,
            ]);
        }

        #[Route('/edit/{id}/{slug}', name: 'app_template_edit',methods: ['GET', 'POST'], requirements: ["slug" => "[a-z0-9\-]*", "year" =>"[0-9]*", "id" =>"[0-9]*"])]
        public function edit(Template $template, String $slug, Request $request, TemplateRepository $templateRepository): Response
        {
            if ($template->getSlug() !== $slug) {
                return $this->redirectToRoute('app_template_new', [
                    'id' => $template->getId(),
                    'slug' => $template->getSlug()
                ], Response::HTTP_SEE_OTHER);
            }

            $form = $this->createFormBuilder($template)
                ->add(child: "coordinates")
                ->getForm()
            ;
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $templateRepository->add($template, true);
                $this->addFlash(
                    'info',
                    'Template updade with success'
                );
                return $this->redirectToRoute('app_template_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('template/edit.html.twig', [
                'template' => $template,
                'form' => $form->createView(),
            ]);
        }

        #[Route('/{id}', name: 'app_template_delete', methods: ['POST'])]
        public function delete(Request $request, Template $template, TemplateRepository $templateRepository): Response
        {
            if ($this->isCsrfTokenValid('delete'.$template->getId(), $request->request->get('_token'))) {
                $templateRepository->remove($template, true);
            }
            $this->addFlash('info', 'You have delete successfully');
            return $this->redirectToRoute('app_template_index', [], Response::HTTP_SEE_OTHER);
        }
    }
