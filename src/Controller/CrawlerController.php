<?php

   namespace App\Controller;

   use GuzzleHttp\Client;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\DomCrawler\Crawler;

   class CrawlerController extends AbstractController
   {
      /**
       * @throws GuzzleException
       */
      public function crawlWebsiteMetaProperty($url, $object): ?string
      {
         // like <meta property="og:site_name" content="site_name"/>
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);
         dump($crawler);
         return $crawler->filter("meta[property='{$object}']")->count()
             ? $crawler->filter("meta[property='{$object}']")->attr('content') : null;
      }
      public function crawlWebsiteTag($url, $object): ?string
      {
         //    <title>tiitle</title>
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);
         return $crawler->filter($object)->count() ? $crawler->filter($object)->text() : null;

      }
      public function crawlWebsiteMetaName($url, $object): ?string
      {
         // like     <meta name="theme-color" content="#e30913"/>
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);
         return $crawler->filter("meta[name='{$object}']")->count()
             ? $crawler->filter("meta[name='{$object}']")->attr('content') : null;
      }

      public function crawlWebsiteMetaPropertyDateFallBack($url, $object): ?string
      {// in case first one is null
//         <span class="posted-on"><time class="entry-date published" datetime="2024-12-05T13:28:28+01:00">5 december 2024 - 13:28</time></span> - CG -
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);
         dump($crawler);
         return $crawler->filter("time.entry-date.published")->count()
             ? $crawler->filter("time.entry-date.published")->attr('datetime') : null;
      }
   }

