<?php

   namespace App\MessageHandler;

   use App\Controller\ScrapeControllers\{
       ScrapeDateTimeController,
       ScrapeDescriptionController,
       ScrapeImageController,
       ScrapeSourceController,
       ScrapeTitleController
   };
   use App\Controller\UrlController\GetUrlArticlesController;
   use App\Entity\News;
   use App\Message\ScrapeWebsiteMessage;
   use Doctrine\ORM\EntityManagerInterface;
   use GuzzleHttp\Client;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Component\DomCrawler\Crawler;
   use Symfony\Component\Messenger\Attribute\AsMessageHandler;

   #[AsMessageHandler]
   final class ScrapeWebsiteMessageHandler
   {
      public function __construct(
          private GetUrlArticlesController    $getUrlArticlesController,
          private EntityManagerInterface      $entityManager,
          private ScrapeTitleController       $scrapeTitleController,
          private ScrapeDescriptionController $scrapeDescriptionController,
          private ScrapeSourceController      $scrapeSourceController,
          private ScrapeImageController       $scrapeImageController,
          private ScrapeDateTimeController    $scrapeDateTimeController
      )
      {
      }

      /**
       * Handles the scraping logic for website articles.
       *
       * @param ScrapeWebsiteMessage $message The scrape message.
       * @return string The result of the scraping process.
       * @throws GuzzleException If an HTTP request fails.
       */
      public function __invoke(ScrapeWebsiteMessage $message): string
      {
         $articleUrls = $this->getUrlArticlesController->fetchNewsUrl($message->getMax());

         if (empty($articleUrls)) {
            return 'No articles found.';
         }

         $client = new Client();

         foreach ($articleUrls as $articleData) {
            $websiteUrl = $articleData['url'];

            try {
               // Fetch and parse the website content
               $response = $client->request('GET', $websiteUrl);
               $html = $response->getBody()->getContents();
               $crawler = new Crawler($html);

               // Scrape content
               $title = $this->scrapeTitleController->scrapeTitle($crawler);
               $description = $this->scrapeDescriptionController->scrapeDescription($crawler);
               $source = $this->scrapeSourceController->scrapeSource($crawler);
               $imageUrl = $this->scrapeImageController->scrapeImage($crawler);
               $dateTime = $this->scrapeDateTimeController->scrapeDateTime($crawler);

               // Persist the scraped data
               try {
                  $news = (new News())
                      ->setTitle($title)
                      ->setDescription($description)
                      ->setSource($source)
                      ->setImageUrl($imageUrl)
                      ->setDate($dateTime)
                      ->setWebsiteUrl($websiteUrl);

                  $this->entityManager->persist($news);
                  $this->entityManager->flush();

               } catch (\Throwable $e) {
                  // Log the exception with context for easier debugging
                  dump([
                      'Database Error' => $e->getMessage(),
                      'URL' => $websiteUrl,
                  ]);
                  continue; // Skip to the next article
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
   }
