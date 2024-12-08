<?php

   namespace App\Command;

   use App\Entity\PersonalAccessToken;
   use App\Repository\UserRepository;
   use Doctrine\ORM\EntityManagerInterface;
   use Random\RandomException;
   use Symfony\Component\Console\Attribute\AsCommand;
   use Symfony\Component\Console\Command\Command;
   use Symfony\Component\Console\Input\InputArgument;
   use Symfony\Component\Console\Input\InputInterface;
   use Symfony\Component\Console\Output\OutputInterface;

   #[AsCommand(
       name: 'app:create-pat',
       description: 'Creates a personal access token',
   )]
   class CreatePatCommand extends Command
   {
      private UserRepository $userRepository;
      private EntityManagerInterface $em;

      public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
      {
         parent::__construct();
         $this->userRepository = $userRepository;
         $this->em = $em;
      }

      protected function configure(): void
      {
         $this
             ->setDescription('Create a personal access token.')
             ->addArgument('email', InputArgument::REQUIRED, 'User email');
      }

      /**
       * @throws RandomException
       */
      protected function execute(InputInterface $input, OutputInterface $output): int
      {
         $email = $input->getArgument('email');


         $user = $this->userRepository->findOneBy(['email' => $email]);
         if (!$user) {
            $output->writeln('<error>User not found</error>');
            return Command::FAILURE;
         }
         $token = bin2hex(random_bytes(32));

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
         $output->writeln('<info>Token:</info> ' . $token);

         return Command::SUCCESS;
      }
   }
