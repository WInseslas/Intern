<?php

    namespace App\Security\Authenticator;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use League\OAuth2\Client\Provider\GoogleUser;
    use Symfony\Component\Routing\RouterInterface;
    use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
    use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
    use Symfony\Component\HttpFoundation\{Request, RedirectResponse, Response};
    use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;


    class GoogleAuthenticator extends OAuth2Authenticator
    {
        private ClientRegistry $clientRegistry;
        private RouterInterface $routerInterface;
        private EntityManagerInterface $entityManagerInterface;


        public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManagerInterface, RouterInterface $routerInterface){
            $this->clientRegistry = $clientRegistry;
            $this->routerInterface = $routerInterface;
            $this->entityManagerInterface = $entityManagerInterface;
        }

        public function supports(Request $request) : bool
        {
            return $request->attributes->get('_route') === "app_connect_google_check" && $request->isMethod('GET');
        }

        public function authenticate(Request $request): Passport
        {
            $client = $this->clientRegistry->getClient('google_main');
            $accessToken = $this->fetchAccessToken($client);

            return new SelfValidatingPassport(
                new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                    /** @var GoogleUser $googleUser */
                    $googleUser = $client->fetchUserFromToken($accessToken);
                    dd($googleUser);

                    $email = $googleUser->getEmail();

                    // have they logged in with Google before? Easy!
                    $existingUser = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);

                    // User doesnt exist, we create it !
                    if (!$existingUser) {
                        $existingUser = new User();
                        $existingUser->setEmail($email);
                        // $existingUser->setGoogleId($googleUser->getId());
                        // $existingUser->setHostedDomain($googleUser->getHostedDomain());
                        $this->entityManagerInterface->persist($existingUser);
                    }
                    $existingUser->setAvatar($googleUser->getAvatar());
                    $this->entityManagerInterface->flush();

                    return $existingUser;
                })
            );
        }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {

            // change "app_profile" to some route in your app
            return new RedirectResponse(
                $this->routerInterface->generate('app_register')
            );

            // or, on success, let the request continue to be handled by the controller
            //return null;
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
        {
            $message = strtr($exception->getMessageKey(), $exception->getMessageData());

            return new Response($message, Response::HTTP_FORBIDDEN);
        }


        public function start(Request $request, AuthenticationException $authException = null): ?Response
        {
            /*
            * If you would like this class to control what happens when an anonymous user accesses a
            * protected page (e.g. redirect to /login), uncomment this method and make this class
            * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
            *
            * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
            */
            return null;
        }

    }