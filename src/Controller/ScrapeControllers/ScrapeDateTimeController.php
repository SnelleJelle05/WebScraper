<?php

   namespace App\Controller\ScrapeControllers;

   use Carbon\Carbon;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScrapeDateTimeController extends AbstractController
   {
      public function scrapeDateTime($crawler): ?string
      {
         assert($crawler instanceof  Crawler);

         // must be saved as ISO 8601 date format
         $dateTime = $crawler->filter("meta[property='article:published_time']")->count()
             ? $crawler->filter("meta[property='article:published_time']")->attr('content') : null;

         if (!$dateTime) {
            $dateTime = $crawler->filter("time.entry-date.published")->count()
                ? $crawler->filter("time.entry-date.published")->attr('datetime') : null;
         }

         if (!$dateTime) {
            $dateTime = $crawler->filter("meta[name='article:published_time']")->count()
                ? $crawler->filter("meta[name='article:published_time']")->attr('content') : null;
         }

         // if the date is in a different format, convert it to ISO 8601
         if ($dateTime) {
            $dateTime = Carbon::parse($dateTime)->format('Ymd\THis\Z');
         }
         return $dateTime;
      }
   }
