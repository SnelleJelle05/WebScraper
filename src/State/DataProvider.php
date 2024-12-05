<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\GetUrlArticlesController;
   use App\Controller\ScraperController;
   use GuzzleHttp\Exception\GuzzleException;

   class DataProvider implements ProviderInterface
   {
      private GetUrlArticlesController $getUrlArticlesController;
      private ScraperController $ScraperController;


      public function __construct(GetUrlArticlesController $getUrlArticlesController, ScraperController $ScraperController)
      {
         $this->getUrlArticlesController = $getUrlArticlesController;
         $this->ScraperController = $ScraperController;
      }

      /**
       * @throws GuzzleException
       * @throws \Exception
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         $articlesUrl = $this->getUrlArticlesController->fetchNewsUrl($context['filters']['max'] );

         return $this->ScraperController->Scrape($articlesUrl);
      }

   }
