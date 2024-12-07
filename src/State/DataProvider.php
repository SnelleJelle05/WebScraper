<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\UrlController\GetUrlArticlesController;
   use App\Message\ScrapeWebsiteMessage;
   use Symfony\Component\Messenger\Exception\ExceptionInterface;
   use Symfony\Component\Messenger\MessageBusInterface;
   use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

   class DataProvider implements ProviderInterface
   {
      private MessageBusInterface $messageBus;
      private GetUrlArticlesController $getUrlArticlesController;

      public function __construct(MessageBusInterface $messageBus, GetUrlArticlesController  $getUrlArticlesController)
      {
         $this->messageBus = $messageBus;
         $this->getUrlArticlesController = $getUrlArticlesController;
      }


      /**
       * @throws TransportExceptionInterface
       * @throws ServerExceptionInterface
       * @throws RedirectionExceptionInterface
       * @throws DecodingExceptionInterface
       * @throws ClientExceptionInterface
       * @throws ExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         $websites = $this->getUrlArticlesController->fetchNewsUrl($context['filters']['max'], $context['filters']['Language/Location']);
         $this->messageBus->dispatch(new ScrapeWebsiteMessage($websites));
         return ['status' => 'Scraping...'];
      }

   }
