<?php

   namespace App\MessageHandler;

   use App\Controller\ScraperController;
   use App\Controller\UrlController\GetUrlArticlesController;
   use App\Message\ScrapeWebsiteMessage;
   use GuzzleHttp\Exception\GuzzleException;
   use mysql_xdevapi\Exception;
   use Symfony\Component\Messenger\Attribute\AsMessageHandler;
   use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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

      /**
       * @throws GuzzleException
       */
      public function __invoke(ScrapeWebsiteMessage $message): void
      {
         try {
            $articlesUrl = $this->getUrlArticlesController->fetchNewsUrl($message->getMax());
            $this->ScraperController->Scrape($articlesUrl);
         } catch (\Exception $e) {
            dump("MessageHandler error: " . $e->getMessage());
         }
      }
   }
