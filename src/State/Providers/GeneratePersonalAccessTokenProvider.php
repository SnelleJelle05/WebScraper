<?php

   namespace App\State\Providers;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\GeneratePersonalAccessTokenController;
   use App\Entity\PersonalAccessToken;
   use App\Entity\User;
   use App\Repository\PersonalAccessTokenRepository;
   use Doctrine\ORM\EntityManagerInterface;
   use phpDocumentor\Reflection\Utils;
   use Random\RandomException;
   use Symfony\Bundle\SecurityBundle\Security;
   use Symfony\Component\Security\Core\Exception\AccessDeniedException;

   class GeneratePersonalAccessTokenProvider implements ProviderInterface
   {

      public function __construct(
         private GeneratePersonalAccessTokenController $generatePersonalAccessTokenController,
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
