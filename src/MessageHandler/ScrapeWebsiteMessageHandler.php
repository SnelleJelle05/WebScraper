<?php

namespace App\MessageHandler;

use App\Controller\GetUrlArticlesController;
use App\Controller\ScraperController;
use App\Message\ScrapeWebsiteMessage;
use GuzzleHttp\Exception\GuzzleException;
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

   /**
    * @throws GuzzleException
    */
   public function __invoke(ScrapeWebsiteMessage $message): void
    {
       $articlesUrl = $this->getUrlArticlesController->fetchNewsUrl(200);
       $this->ScraperController->Scrape($articlesUrl);
    }
}
