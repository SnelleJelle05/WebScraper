<?php

   namespace App\State\Providers;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\PersonalAccessToken\GeneratePersonalAccessTokenController;
   use Random\RandomException;

   class GeneratePersonalAccessTokenProvider implements ProviderInterface
   {

      public function __construct(
         private readonly GeneratePersonalAccessTokenController $generatePersonalAccessTokenController,
      )
      {
      }

      /**
       * @throws RandomException
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
      {

         $token = $this->generatePersonalAccessTokenController->generatePAT();
         dump($token);
         return ['token' => $token];
      }
   }
