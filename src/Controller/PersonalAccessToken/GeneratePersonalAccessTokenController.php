<?php

   namespace App\Controller\PersonalAccessToken;

   use App\Entity\PersonalAccessToken;
   use App\Entity\User;
   use Doctrine\ORM\EntityManagerInterface;
   use Random\RandomException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Bundle\SecurityBundle\Security;
   use Symfony\Component\Security\Core\Exception\AccessDeniedException;

   class GeneratePersonalAccessTokenController extends AbstractController
   {
      public function __construct(
          private readonly Security               $security,
          private readonly EntityManagerInterface $em,
      )
      {
      }

      /**
       * @throws RandomException
       */
      public function generatePAT(): array
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
         return ['token' => $token];
      }
   }
