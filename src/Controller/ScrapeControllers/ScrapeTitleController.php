<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class ScrapeTitleController extends AbstractController
   {

      public function scrapeTitle($crawler): ?string
      {
         $title = $crawler->filter("meta[property='og:title']")->count()
             ? $crawler->filter("meta[property='og:title']")->attr('content') : null;

         if (!$title) {
            $title = $crawler->filter('.article-title')->count()
                ? $crawler->filter('.article-title')->text()
                : null;
         }

         // gets the <title> tag
         if (!$title) {
            $title = $crawler->filter('title')->count()
                ? $crawler->filter('title')->text()
                : null;
         }

         return $title;
      }
   }
