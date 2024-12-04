<?php

   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use GuzzleHttp\Client;
   use Symfony\Component\DomCrawler\Crawler;

   class ScraperController extends AbstractController
   {

      public function Scrape($response): array
      {
         foreach ($response as $article) {
            $url = $article['url'];
            $client = new Client();

            $response = $client->request('GET', $url);

            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);
            dump($crawler);
            $title = $crawler->filter('title')->count() ? $crawler->filter('title')->text() : null;

            $description = $crawler->filter('meta[name="description"]')->count()
                ? $crawler->filter('meta[name="description"]')->attr('content') : null;

            $source = $crawler->filter('meta[name="application-name"]')->count()
                ? $crawler->filter('meta[property="application-name"]')->attr('content') : null;

            $imageUrl = $crawler->filter('meta[property="og:image"]')->count()
                ? $crawler->filter('meta[property="og:image"]')->attr('content') : null;

            $dateTime = $crawler->filter('meta[property="article:published_time"]')->count()
                ? $crawler->filter('meta[property="article:published_time"]')->attr('content') : null;

            $array[] = ['Title' => $title, 'Description' => $description, 'Source' => $source, 'ImageUrl' => $imageUrl, 'Date' => $dateTime];
         }
         return $array;

      }

   }
