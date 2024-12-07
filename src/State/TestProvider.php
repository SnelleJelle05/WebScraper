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
             ["url" => 'https://1035kissfmboise.com/post-malone-spotted-at-boise-state-game-hangs-at-downtown-bar-photos/'],
         ];
         return $this->ScraperController->Scrape($arr);

      }
   }
