<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScrapeImageController extends AbstractController
   {
      public function scrapeImage($crawler): ?string
      {
         assert($crawler instanceof  Crawler);

         $image = $crawler->filter("meta[property='og:image']")->count()
             ? $crawler->filter("meta[property='og:image']")->attr('content') : null;

         if (!$image) {
            // <img src="image.jpg">
            $image = $crawler->filter("img")->count()
                ? $crawler->filter("img")->attr("src") : null;
         }

         return $image;
      }
   }
