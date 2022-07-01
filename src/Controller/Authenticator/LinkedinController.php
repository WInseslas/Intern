<?php

    namespace App\Controller\Authenticator;

    use Symfony\Component\Routing\Annotation\Route;
    use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\{Request, JsonResponse, RedirectResponse};


    class LinkedinController extends AbstractController
    {
        /**
         * Link to this controller to start the "connect" process
         */
        #[Route(path: '/connect/linkedin', name: 'app_connect_linkedin_start')]
        public function connectAction(ClientRegistry $clientRegistry)
        {
           
        }

       
        #[Route(path: '/connect/linkedin/check', name: 'app_connect_linkedin_check')]
        public function connectCheckAction(Request $request)
        {
            
        }
    }