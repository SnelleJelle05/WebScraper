<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\GetUrlArticlesController;

   class DataProvider implements ProviderInterface
   {
      private GetUrlArticlesController $getUrlArticlesController;


      public function __construct(GetUrlArticlesController $getUrlArticlesController )
      {
         $this->getUrlArticlesController = $getUrlArticlesController;

      }

      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         dump($context['filters']['max']);
         $response = $this->getUrlArticlesController->fetchNewsUrl(10 );


         dump($response);
         return $response;
      }

   }
