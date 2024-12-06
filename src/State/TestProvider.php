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
             ["url" => 'https://www.xgn.nl/artikel/reageer-en-win-gratis-bioscoopkaartjes-voor-wicked?comment=6043'],
//             ["url" => 'https://www.racexpress.nl/le-mans/porsche-x-iron-dames-because-every-dream-matters/n/141307']
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
