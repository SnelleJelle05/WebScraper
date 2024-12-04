<?php

   namespace App\Controller;

   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use GuzzleHttp\Client;
   use Symfony\Component\DomCrawler\Crawler;

   class ScraperController extends AbstractController
   {

      /**
       * @throws GuzzleException
       */
      public function Scrape($response): array
      {
         foreach ($response as $article) {
            $url = $article['url'];

            $title = $this->crawlWebsiteTag($url, 'title');

            $description = $this->crawlWebsiteMetaName($url, 'description');

            $source = $this->crawlWebsiteMetaName($url, 'application-name');

            $imageUrl = $this->crawlWebsiteMetaProperty($url, 'og:image');

            $dateTime = $this->crawlWebsiteMetaProperty($url, 'article:published_time');

            $array[] = ['Title' => $title, 'Description' => $description, 'Source' => $source, 'ImageUrl' => $imageUrl, 'Date' => $dateTime];
         }
         return $array;
      }



      /**
       * @throws GuzzleException
       */
      private function crawlWebsiteMetaName($url, $object): ?string
      {
         $client = new Client();

         $response = $client->request('GET', $url);

         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);

         return $crawler->filter("meta[name='{$object}']")->count()
             ? $crawler->filter("meta[name='{$object}']")->attr('content') : null;
      }

      private function crawlWebsiteMetaProperty($url, $object): ?string
      {
         $client = new Client();

         $response = $client->request('GET', $url);

         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);

         return $crawler->filter("meta[property='{$object}']")->count()
             ? $crawler->filter("meta[property='{$object}']")->attr('content') : null;
      }

      private function crawlWebsiteTag($url, $object): ?string
      {
         $client = new Client();
         $response = $client->request('GET', $url);
         $html = $response->getBody()->getContents();
         $crawler = new Crawler($html);

         return $crawler->filter($object)->count() ? $crawler->filter($object)->text() : null;

      }

   }
