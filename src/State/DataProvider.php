<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\GetUrlArticlesController;
   use App\Controller\ScraperController;

   class DataProvider implements ProviderInterface
   {
      private GetUrlArticlesController $getUrlArticlesController;
      private ScraperController $ScraperController;


      public function __construct(GetUrlArticlesController $getUrlArticlesController, ScraperController $ScraperController)
      {
         $this->getUrlArticlesController = $getUrlArticlesController;
         $this->ScraperController = $ScraperController;
      }

      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         $response = $this->getUrlArticlesController->fetchNewsUrl($context['filters']['max'] );

         $data = $this->ScraperController->Scrape($response);
         dump($data);

         return $data;
      }

   }
