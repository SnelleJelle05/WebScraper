<?php

   namespace App\MessageHandler;

   use App\Controller\ScraperController;
   use App\Controller\UrlController\GetUrlArticlesController;
   use App\Message\ScrapeWebsiteMessage;
   use Symfony\Component\Messenger\Attribute\AsMessageHandler;

   #[AsMessageHandler]
   final class ScrapeWebsiteMessageHandler
   {
      private GetUrlArticlesController $getUrlArticlesController;
      private ScraperController $ScraperController;


      public function __construct(GetUrlArticlesController $getUrlArticlesController, ScraperController $ScraperController)
      {
         $this->getUrlArticlesController = $getUrlArticlesController;
         $this->ScraperController = $ScraperController;
      }

      public function __invoke(ScrapeWebsiteMessage $message): void
      {
         try {
            $articlesUrl = $this->getUrlArticlesController->fetchNewsUrl();
            $this->ScraperController->Scrape($articlesUrl);
         } catch (\Exception $e) {
            dump("MessageHandler error: " . $e->getMessage());
         }
      }
   }
