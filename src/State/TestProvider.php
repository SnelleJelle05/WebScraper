<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Message\ScrapeWebsiteMessage;
   use GuzzleHttp\Exception\GuzzleException;
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
       * @throws GuzzleException
       * @throws \Exception
       * @throws ExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): \Symfony\Component\Messenger\Envelope
      {
         $websiteUrls = [
//             ["url" => 'https://abc6onyourside.com/news/local/patients-worried-about-coverage-amid-osu-insurance-contract-battle-anthem-blue-cross-blue-shield-contract-negotiation-standoff'],
             ["url" => 'https://www.oklahomacitysun.com/news/274831735/seton-hall-seeks-ame-page-mentality-in-game-vs-oklahoma-state'],
         ];

         return $this->messageBus->dispatch(new ScrapeWebsiteMessage($websiteUrls));

      }
   }
