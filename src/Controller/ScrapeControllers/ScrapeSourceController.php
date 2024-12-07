<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class ScrapeSourceController extends AbstractController
   {

      public function scrapeSource($crawler): ?string
      {
         $source = $crawler->filter("meta[property='og:site_name']")->count()
             ? $crawler->filter("meta[property='og:site_name']")->attr('content') : null;


         return $source;
      }
   }
