<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Message\ScrapeWebsiteMessage;
   use Symfony\Component\Messenger\Exception\ExceptionInterface;
   use Symfony\Component\Messenger\MessageBusInterface;

   class DataProvider implements ProviderInterface
   {
      private MessageBusInterface $messageBus;
      public function __construct(MessageBusInterface $messageBus)
      {
         $this->messageBus = $messageBus;
      }

      /**
       * @throws ExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         $this->messageBus->dispatch(new ScrapeWebsiteMessage(($context['filters']['max'])));
         return ['status' => 'Scraping...'];
      }

   }
