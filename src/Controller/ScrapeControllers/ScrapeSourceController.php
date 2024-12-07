<?php

   namespace App\Controller\ScrapeControllers;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScrapeSourceController extends AbstractController
   {

      public function scrapeSource($crawler): ?string
      {
         assert($crawler instanceof Crawler);

         $source = $crawler->filter("meta[property='og:site_name']")->count()
             ? $crawler->filter("meta[property='og:site_name']")->attr('content') : null;


         // last resort gets the twitter site name and removed the @ symbol
         if (!$source) {
            $source = $crawler->filter("meta[name='twitter:site']")->count()
                ? $crawler->filter("meta[name='twitter:site']")->attr('content') : null;
            //check not null and remove @ symbol
            if ($source) {
               $source = ltrim($source, '@');
            }
         }

         return $source;
      }
   }
