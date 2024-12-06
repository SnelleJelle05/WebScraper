<?php

namespace App\Controller\ScrapeControllers;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeDescriptionController extends AbstractController
{
   public function scrapeDescription($url): ?string
   {
      $client = new Client();
      $response = $client->request('GET', $url);
      $html = $response->getBody()->getContents();
      $crawler = new Crawler($html);

      $description =  $crawler->filter("meta[property='og:description']")->count()
          ? $crawler->filter("meta[property='og:description']")->attr('content') : null;


      return $description;
   }
}
