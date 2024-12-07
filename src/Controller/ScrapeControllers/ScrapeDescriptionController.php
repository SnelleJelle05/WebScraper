<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScrapeDescriptionController extends AbstractController
   {
      public function scrapeDescription($crawler): ?string
      {
         assert($crawler instanceof  Crawler);

         $description = $crawler->filter("meta[property='og:description']")->count()
             ? $crawler->filter("meta[property='og:description']")->attr('content') : null;


         return $description;
      }
   }
