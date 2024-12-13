<?php

   namespace App\Security;

   use App\Repository\PersonalAccessTokenRepository;
   use App\Repository\UserRepository;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
   use Symfony\Component\Security\Core\Exception\AuthenticationException;
   use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
   use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

   class PatAuthenticator extends AbstractAuthenticator
   {
      private PersonalAccessTokenRepository $personalAccessTokenRepository;
      private UserRepository $userRepository;

      public function __construct(PersonalAccessTokenRepository $personalAccessTokenRepository, UserRepository $userRepository)
      {
         $this->personalAccessTokenRepository = $personalAccessTokenRepository;
         $this->userRepository = $userRepository;
      }

      public function supports(Request $request): ?bool
      {
         dump("1234");
         return $request;
         }

      public function authenticate(Request $request): SelfValidatingPassport
      {

         // get the token from the Authorization header
//         $token = $request->headers->get('Authorization');
//         $token = str_replace('Bearer ', '', $token);
//
//         $pat = $this->personalAccessTokenRepository->findOneBy(['token' => $token]);
//
//         return new SelfValidatingPassport(
//             new UserBadge($pat->getUser()->getEmail(), function ($userIdentifier) {
//                return $this->userRepository->findOneByEmail($userIdentifier);
//             })
//         );
         dump('authenticate');
         return true;


      }

      public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
      {
         return null; // Let the request continue.
      }

      public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
      {
         return new Response('error: Authentication failed', 401);
      }
   }
