<?php

   namespace App\Controller\ScrapeControllers;

   use GuzzleHttp\Client;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class ScrapeImageController extends AbstractController
   {
      /**
       * @throws GuzzleException
       */
      public function scrapeImage($url): ?string
      {
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);

         $image =  $crawler->filter("meta[property='og:image']")->count()
             ? $crawler->filter("meta[property='og:image']")->attr('content') : null;

         if (!$image) {
            // <img src="image.jpg">
            $image = $crawler->filter("img")->count()
                ? $crawler->filter("img")->attr("src") : null;
         }

         return $image;
      }
   }
