<?php

   namespace App\Controller;

   use App\Repository\NewsRepository;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class ScraperController extends AbstractController
   {
      private NewsRepository $newsRepository;
      private CrawlerController $crawlerController;


      public function __construct(NewsRepository $newsRepository, CrawlerController $crawlerController)
      {
         $this->newsRepository = $newsRepository;
         $this->crawlerController = $crawlerController;
      }
      /**
       * @throws GuzzleException
       */
      public function Scrape($response): array
      {
         foreach ($response as $article) {
            $websiteUrl = $article['url'];

            $title = $this->crawlerController->crawlWebsiteMetaProperty($websiteUrl, 'og:title');

            $description = $this->crawlerController->crawlWebsiteMetaProperty($websiteUrl, 'og:site_name');

            $source = $this->crawlerController->crawlWebsiteMetaProperty($websiteUrl, 'og:site_name');

            $imageUrl = $this->crawlerController->crawlWebsiteMetaProperty($websiteUrl, 'og:image');

            $dateTime = $this->crawlerController->crawlWebsiteMetaProperty($websiteUrl, 'article:published_time');
            if ($dateTime == null){
               $dateTime = $this->crawlerController->crawlWebsiteMetaPropertyDateFallBack($websiteUrl, 'article:published_time');
            }

            $array[] = ['Title' => $title, 'Description' => $description, 'Source' => $source, 'ImageUrl' => $imageUrl, 'Date' => $dateTime , 'WebsiteUrl' => $websiteUrl];

            $this->newsRepository->SaveArticle($title, $description, $source, $imageUrl, $dateTime, $websiteUrl);
         }
         return $array;
      }
   }
