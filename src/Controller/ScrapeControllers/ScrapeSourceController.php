<?php

namespace App\Controller\ScrapeControllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeSourceController extends AbstractController
{
   /**
    * @throws GuzzleException
    */
   public function scrapeSource($url): ?string
   {
      $client = new Client();
      $response = $client->request('GET', $url);
      $html = $response->getBody()->getContents();
      $crawler = new Crawler($html);

      $source =  $crawler->filter("meta[property='og:name']")->count()
          ? $crawler->filter("meta[property='og:name']")->attr('content') : null;


      return $source;
   }
}
