<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Message\ScrapeWebsiteMessage;
   use Symfony\Component\Messenger\Exception\ExceptionInterface;
   use Symfony\Component\Messenger\MessageBusInterface;

   class TestProvider implements ProviderInterface
   {
      private MessageBusInterface $messageBus;

      public function __construct(MessageBusInterface $messageBus)
      {
         $this->messageBus = $messageBus;
      }

      /**
       * @throws \Exception
       * @throws ExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): \Symfony\Component\Messenger\Envelope
      {
         $websiteUrls = [
//             ["url" => 'https://abc6onyourside.com/news/local/patients-worried-about-coverage-amid-osu-insurance-contract-battle-anthem-blue-cross-blue-shield-contract-negotiation-standoff'],
             ["url" => 'https://921thebeat.iheart.com/content/2024-12-07-timeless-hit-crowned-most-popular-christmas-song-of-all-time/'],
         ];

         return $this->messageBus->dispatch(new ScrapeWebsiteMessage($websiteUrls));

      }
   }
