<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Controller\ScraperController;
   use GuzzleHttp\Exception\GuzzleException;

   class TestProvider implements ProviderInterface
   {
      private ScraperController $ScraperController;

      public function __construct(ScraperController $ScraperController)
      {
         $this->ScraperController = $ScraperController;
      }

      /**
       * @throws GuzzleException
       * @throws \Exception
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
      {
         $arr = [
             ["url" => 'https://www.wvasfm.org/2024-12-07/internet-sleuths-are-trying-to-solve-the-new-york-ceo-killing'],
             ["url" => 'https://www.apr.org/science-health/2024-12-07/how-many-species-could-go-extinct-from-climate-change-it-depends-on-how-hot-it-gets'],
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
