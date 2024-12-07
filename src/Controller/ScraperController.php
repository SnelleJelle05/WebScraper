<?php

   namespace App\Controller;

   use App\Controller\ScrapeControllers\ScrapeDateTimeController;
   use App\Controller\ScrapeControllers\ScrapeDescriptionController;
   use App\Controller\ScrapeControllers\ScrapeImageController;
   use App\Controller\ScrapeControllers\ScrapeSourceController;
   use App\Controller\ScrapeControllers\ScrapeTitleController;
   use App\Repository\NewsRepository;
   use GuzzleHttp\Client;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScraperController extends AbstractController
   {
      private  NewsRepository $newsRepository;
      private ScrapeTitleController $scrapeTitleController;
      private ScrapeDescriptionController $scrapeDescriptionController;
      private ScrapeSourceController $scrapeSourceController;
      private ScrapeImageController $scrapeImageController;
      private ScrapeDateTimeController $scrapeDateTimeController;

      public function __construct(
          NewsRepository              $newsRepository,
          ScrapeTitleController       $scrapeTitleController,
          ScrapeDescriptionController $scrapeDescriptionController,
          ScrapeSourceController      $scrapeSourceController,
          ScrapeImageController       $scrapeImageController,
          ScrapeDateTimeController    $scrapeDateTimeController
      )
      {
         $this->newsRepository = $newsRepository;
         $this->scrapeTitleController = $scrapeTitleController;
         $this->scrapeDescriptionController = $scrapeDescriptionController;
         $this->scrapeSourceController = $scrapeSourceController;
         $this->scrapeImageController = $scrapeImageController;
         $this->scrapeDateTimeController = $scrapeDateTimeController;
      }

      /**
       * @throws GuzzleException
       */
      public function Scrape($response): array
      {
         foreach ($response as $item) {
            $websiteUrl = $item['url'];
            try {
               // makes a new crawler object, and uses for each scrape the same for better performance.
               $client = new Client();
               $response = $client->request('GET', $websiteUrl);
               $html = $response->getBody()->getContents();
               $crawler = new Crawler($html);
               dump($crawler);

               // gets the $var from the website
               $title = $this->scrapeTitleController->scrapeTitle($crawler);
               $description = $this->scrapeDescriptionController->scrapeDescription($crawler);
               $source = $this->scrapeSourceController->scrapeSource($crawler);
               $imageUrl = $this->scrapeImageController->scrapeImage($crawler);
               $dateTime = $this->scrapeDateTimeController->scrapeDateTime($crawler);

               $array[] = ['Title' => $title, 'Description' => $description, 'Source' => $source, 'ImageUrl' => $imageUrl, 'Date' => $dateTime, 'WebsiteUrl' => $websiteUrl];
               $this->newsRepository->SaveArticle($title, $description, $source, $imageUrl, $dateTime, $websiteUrl); //saves to database
            } catch (\Exception $e) {
               // error handling
               dump("Scape error: " . $e);
            }
         }
         // error handling
         if (empty($array)) {
            return ['status' => 'No articles found.'];
         }
         return $array;
      }
   }
