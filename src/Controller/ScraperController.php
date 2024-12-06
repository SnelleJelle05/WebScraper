<?php

   namespace App\Controller;

   use App\Controller\ScrapeControllers\ScrapeDateTimeController;
   use App\Controller\ScrapeControllers\ScrapeDescriptionController;
   use App\Controller\ScrapeControllers\ScrapeImageController;
   use App\Controller\ScrapeControllers\ScrapeSourceController;
   use App\Controller\ScrapeControllers\ScrapeTitleController;
   use App\Repository\NewsRepository;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class ScraperController extends AbstractController
   {
      private NewsRepository $newsRepository;
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
         foreach ($response as $article) {
            $websiteUrl = $article['url'];

            $title = $this->scrapeTitleController->scrapeTitle($websiteUrl);
            $description = $this->scrapeDescriptionController->scrapeDescription($websiteUrl);
            $source = $this->scrapeSourceController->scrapeSource($websiteUrl);
            $imageUrl = $this->scrapeImageController->scrapeImage($websiteUrl);

            $dateTime = $this->scrapeDateTimeController->scrapeDateTime($websiteUrl);

            $array[] = ['Title' => $title, 'Description' => $description, 'Source' => $source, 'ImageUrl' => $imageUrl, 'Date' => $dateTime, 'WebsiteUrl' => $websiteUrl];

            $this->newsRepository->SaveArticle($title, $description, $source, $imageUrl, $dateTime, $websiteUrl); // dont save to db
            return $array;
         }
         return ['There was an error'];
      }
   }
