<?php

namespace App\Controller\ScrapeControllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeTitleController extends AbstractController
{
   /**
    * @throws GuzzleException
    */
   public function scrapeTitle($url): ?string
   {
      $client = new Client();
      $response = $client->request('GET', $url);
      $html = $response->getBody()->getContents();
      $crawler = new Crawler($html);

      $title =  $crawler->filter("meta[property='og:title']")->count()
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
