<?php

   namespace App\Security;

   use App\Repository\PersonalAccessTokenRepository;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
   use Symfony\Component\Security\Core\Exception\AuthenticationException;
   use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
   use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
   use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
   use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

   class PersonalAccessTokenAuthenticator extends AbstractAuthenticator
   {

      public function __construct(private PersonalAccessTokenRepository $personalAccessTokenRepository)
      {
      }

      public function supports(Request $request): ?bool
      {
         //used on every /api request
         return (str_starts_with($request->getPathInfo(), '/api/v1/news') && $request->isMethod('GET'));
      }

      public function authenticate(Request $request): Passport
      {
         $token = $this->personalAccessTokenRepository->findUserByToken($request->query->get('apiKey'));

         if (!$token) {
            throw new AuthenticationException('Invalid API Key');
         }

         return new Passport(
             new UserBadge($token->getRelatedUser()->getEmail()), // User identification, e.g., the email
             new CustomCredentials(
                 function ($credentials, $user) {
                    // Logic to validate the credentials (token in this case)
                    // if are the same return to onAuthenticationSuccess
                    return $credentials->getToken() === $user->getPersonalAccessToken()->getToken(); // Assuming the token is stored as the API key
                 },
                 $token->getRelatedUser()->getPersonalAccessToken() // The API key to be validated
             )
         );
      }

      public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
      {
         // If the token is valid, the user is authenticated
         return null;
      }

      public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
      {
         throw new AuthenticationException('Authentication Failed, check tour API Key');
      }
   }