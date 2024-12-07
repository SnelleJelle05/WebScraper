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
//             ["url" => 'https://www.jdsupra.com/legalnews/texas-federal-court-temporarily-blocks-9635704/'],
             ["url" => 'https://www.ledgertranscript.com/Temple-Cookies-With-Olaf-58260383'],
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
