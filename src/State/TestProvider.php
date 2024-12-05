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
             ["url" => 'https://www.westerwoldeactueel.nl/2024/12/05/gezellige-kerstmarkt-in-woonservicecentrum-kloosterheerd/'],
             ["url" => 'https://www.telegraaf.nl/vrouw/581748881/perfect-recept-voor-tijdens-de-feestdagen-luchtige-citroengrasmascarpone']
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
