<?php

   namespace App\State\Providers;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Entity\PersonalAccessToken;
   use App\Entity\User;
   use App\Repository\PersonalAccessTokenRepository;
   use Doctrine\ORM\EntityManagerInterface;
   use PHPUnit\Util\Json;
   use Random\RandomException;
   use Symfony\Bundle\SecurityBundle\Security;
   use Symfony\Component\Security\Core\Exception\AccessDeniedException;
   use function PHPUnit\Framework\assertNotEmpty;

   class GeneratePersonalAccessTokenProvider implements ProviderInterface
   {


      public function __construct(private readonly Security $security, private EntityManagerInterface $em, private PersonalAccessTokenRepository $personalAccessTokenRepository)
      {
      }

      /**
       * @throws RandomException
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
      {
         $user = $this->security->getUser();

         if (!$user) {
            throw new AccessDeniedException('User not found');
         }

         assert($user instanceof User);

         // Format the token to match your pattern
         $rawToken = bin2hex(random_bytes(16));
         $token = substr($rawToken, 0, 5) . '-' .
             substr($rawToken, 5, 5) . '-' .
             substr($rawToken, 10, 5) . '-' .
             substr($rawToken, 15, 5) . '-' .
             substr($rawToken, 20, 5);


         if ($user->getPersonalAccessToken()) {
            $pat = $user->getPersonalAccessToken()->setToken($token);
         } else {
            $pat = new PersonalAccessToken();
            $pat->setToken($token);
            $pat->setRelatedUser($user);
         }

         // Save the token
         $this->em->persist($pat);
         $this->em->flush();
         dump($user->getPersonalAccessToken());
         return ['token' => $token];
      }
   }
