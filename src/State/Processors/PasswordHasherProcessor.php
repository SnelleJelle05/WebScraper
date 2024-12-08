<?php

   namespace App\State\Processors;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProcessorInterface;
   use App\Entity\User;
   use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
   use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

   #[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
   class PasswordHasherProcessor implements ProcessorInterface
   {
      public function __construct(
          private readonly ProcessorInterface          $processor,
          private readonly UserPasswordHasherInterface $userPasswordHasher
      )
      {
      }

      public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
      {
         if ($data instanceof User) {
            if ($password = $data->getPlainPassword()) {
               $data->setPassword($this->userPasswordHasher->hashPassword($data, $password));
               $data->eraseCredentials();
            }
         }
         return $this->processor->process($data, $operation, $uriVariables, $context);
      }
   }
