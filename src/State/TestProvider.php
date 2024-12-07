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
             ["url" => 'https://www.earthquakenewstoday.com/2024/12/07/minor-earthquake-2-82-mag-was-detected-near-petrolia-in-ca-3/'],
//             ["url" => 'https://www.aol.com/rusty-elderly-pup-enjoying-retirement-110340770.html'],
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
