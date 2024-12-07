<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class ScrapeDescriptionController extends AbstractController
   {
      public function scrapeDescription($crawler): ?string
      {

         $description = $crawler->filter("meta[property='og:description']")->count()
             ? $crawler->filter("meta[property='og:description']")->attr('content') : null;


         return $description;
      }
   }
