<?php

    namespace App\Controller\Authenticator;

    use Symfony\Component\Routing\Annotation\Route;
    use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
    use Symfony\Component\HttpFoundation\{Request, JsonResponse};
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



    class GoogleController extends AbstractController
    {
        /**
         * Link to this controller to start the "connect" process
         */
        #[Route(path: '/connect/google', name: 'app_connect_google_start')]
        public function connectAction(ClientRegistry $clientRegistry)
        {
            return $clientRegistry->getClient('google_main')->redirect([], []);
        }

        /**
         * After going to Google, you're redirected back here
         * because this is the "redirect_route" you configured
         * in config/packages/knpu_oauth2_client.yaml
         */
        #[Route(path: '/connect/google/check', name: 'app_connect_google_check')]
        public function connectCheckAction(Request $request)
        {
            // ** if you want to *authenticate* the user, then
            // leave this method blank and create a Guard authenticator
            // (read below)

            // if (!$this->getUser()) {
            //     return new JsonResponse(['status' => false, 'message' => "User not found !"]);
            // } else{
            //     return $this->redirectToRoute("app_login");            
            // }
        }
    }