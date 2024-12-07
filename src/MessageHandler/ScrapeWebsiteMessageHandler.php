<?php

   namespace App\MessageHandler;

   use App\Repository\NewsRepository;
   use App\Controller\ScrapeControllers\{
       ScrapeDateTimeController,
       ScrapeDescriptionController,
       ScrapeImageController,
       ScrapeSourceController,
       ScrapeTitleController
   };
   use App\Message\ScrapeWebsiteMessage;
   use GuzzleHttp\Client;
   use Symfony\Component\DomCrawler\Crawler;
   use Symfony\Component\Messenger\Attribute\AsMessageHandler;

   #[AsMessageHandler]
   final class ScrapeWebsiteMessageHandler
   {
      public function __construct(
          private NewsRepository              $newsRepository,
          private ScrapeTitleController       $scrapeTitleController,
          private ScrapeDescriptionController $scrapeDescriptionController,
          private ScrapeSourceController      $scrapeSourceController,
          private ScrapeImageController       $scrapeImageController,
          private ScrapeDateTimeController    $scrapeDateTimeController
      )
      {
      }

      public function __invoke(ScrapeWebsiteMessage $message): string
      {
         $websites = $message->getwebsites();

         if (!$websites) {
            throw new \InvalidArgumentException('No website URLs provided.');
         }
         $client = new Client();

         foreach ($websites as $website) {
            try {
               $websiteUrl = $website['url'];
               $response = $client->request('GET', $websiteUrl);
               $html = $response->getBody()->getContents();
               $crawler = new Crawler($html);

               $data = $this->CrawlData($crawler, $website);

               // Persist the scraped data
               $this->newsRepository->SaveArticle($data);

            } catch (\Throwable $e) {
               // Log the exception with context for easier debugging
               dump([
                   'Scrape Error' => $e->getMessage(),
                   'Code' => $e->getCode(),
                   'URL' => $websiteUrl,
               ]);
               continue; // Skip to the next article
            }
         }
         return 'Scrape finished successfully.';
      }


      private function CrawlData($crawler, $website)
      {

         // Scrape content
         $title = $website['title'];
         if ($title) {
            $title = $this->scrapeTitleController->scrapeTitle($crawler);
         }

         $description = $this->scrapeDescriptionController->scrapeDescription($crawler);

         $source = $website['domain'];
         if (!$source) {
            $source = $this->scrapeSourceController->scrapeSource($crawler);
         }

         $imageUrl = $website['socialimage'];
         if (!$imageUrl) {
            $imageUrl = $this->scrapeImageController->scrapeImage($crawler);
         }

         $dateTime = $website['seendate'];
         if (!$dateTime) {
            $dateTime = $this->scrapeDateTimeController->scrapeDateTime($crawler);
         }

         return [
             'title' => $title,
             'description' => $description,
             'source' => $source,
             'imageUrl' => $imageUrl,
             'dateTime' => $dateTime,
             'websiteUrl' => $website['url']
         ];
      }
   }

