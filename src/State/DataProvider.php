<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use Symfony\Contracts\HttpClient\HttpClientInterface;
   use App\Controller\GetUrlArticlesController;

   class DataProvider implements ProviderInterface
   {
      private HttpClientInterface $client;
      private GetUrlArticlesController $getUrlArticlesController;


      public function __construct(HttpClientInterface $client, GetUrlArticlesController $getUrlArticlesController )
      {
         $this->client = $client;
         $this->getUrlArticlesController = $getUrlArticlesController;

      }

      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         // Haal nieuwsartikelen op via de fetchNews-methode
         $response = $this->getUrlArticlesController->fetchNewsUrl(10 ,   "nl", "'NLD" );
         dump($response);
         return $response;
      }

   }
