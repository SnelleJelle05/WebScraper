<?php

   namespace App\MessageHandler;

   use App\Controller\Funtions\SentimentAnalyzerController;
   use App\Controller\ScrapeControllers\{ScrapeDateTimeController,
       ScrapeDescriptionController,
       ScrapeImageController,
       ScrapeSourceController,
       ScrapeTitleController};
   use App\Message\ScrapeWebsiteMessage;
   use App\Repository\NewsRepository;
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
          private ScrapeDateTimeController    $scrapeDateTimeController,
          private SentimentAnalyzerController $sentimentAnalyzerController,
      )
      {
      }

      public function __invoke(ScrapeWebsiteMessage $message): string
      {
         $websites = $message->getWebsites();
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

               if ($data['title']) {
                  //only analyze sentiment if the title is not empty
                  $sentiment = $this->sentimentAnalyzerController->analyzerSentiment($data);
                  $data['sentiment'] = $sentiment;

                  // must have a title to save the article
                  $this->newsRepository->SaveArticle($data);
               }
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

      private function CrawlData($crawler, $website): array
      {
         // Scrape content
         $title = $website['title'] ?? null;
         if (!$title) {
            $title = $this->scrapeTitleController->scrapeTitle($crawler);
         }

         $description = $this->scrapeDescriptionController->scrapeDescription($crawler) ?? null;

         $source = $website['domain'] ?? null;
         if (!$source) {
            $source = $this->scrapeSourceController->scrapeSource($crawler);
         }

         $imageUrl = $website['socialimage'] ?? null;
         if (!$imageUrl) {
            $imageUrl = $this->scrapeImageController->scrapeImage($crawler);
         }

         $dateTime = $website['seendate'] ?? null;
         if (!$dateTime) {
            $dateTime = $this->scrapeDateTimeController->scrapeDateTime($crawler);
         }

         return [
             'title' => $title,
             'description' => $description,
             'source' => $source,
             'imageUrl' => $imageUrl,
             'dateTime' => $dateTime,
             'websiteUrl' => $website['url'],
             'language' => $website['language'],
             'sourcecountry' => $website['sourcecountry'],
         ];
      }
   }

