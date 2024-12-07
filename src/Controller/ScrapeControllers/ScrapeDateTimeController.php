<?php

namespace App\Controller\ScrapeControllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeDateTimeController extends AbstractController
{
   /**
    * @throws GuzzleException
    */
   public function scrapeDateTime($url): ?string
   {
      $client = new Client();
      $response = $client->request('GET', $url);
      $html = $response->getBody()->getContents();
      $crawler = new Crawler($html);

      $title =  $crawler->filter("meta[property='article:published_time']")->count()
          ? $crawler->filter("meta[property='article:published_time']")->attr('content') : null;

      if ($title == null){
         $title = $crawler->filter("time.entry-date.published")->count()
             ? $crawler->filter("time.entry-date.published")->attr('datetime') : null;
      }

      if ($title == null){
         $title = $crawler->filter("meta[name='article:published_time']")->count()
             ? $crawler->filter("meta[name='article:published_time']")->attr('content') : null;
      }


      return $title;
   }
}
