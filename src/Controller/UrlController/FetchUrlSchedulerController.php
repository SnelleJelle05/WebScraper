<?php

   namespace App\Controller\UrlController;

   use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
   use Symfony\Contracts\HttpClient\HttpClientInterface;

   readonly class FetchUrlSchedulerController
   {

      public function __construct(private HttpClientInterface $client)
      {
      }

      /**
       * @throws TransportExceptionInterface
       * @throws ServerExceptionInterface
       * @throws RedirectionExceptionInterface
       * @throws DecodingExceptionInterface
       * @throws ClientExceptionInterface
       */
      public function fetchNewsUrlSchedule(): array
      {
         $response = $this->requestArticles(25);

         return $response['articles'] ?? [];
      }

      /**
       * @throws DecodingExceptionInterface
       * @throws ClientExceptionInterface
       * @throws ServerExceptionInterface
       * @throws RedirectionExceptionInterface
       * @throws TransportExceptionInterface
       */
      private function requestArticles($max): array
      {
         $responseUs = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => 'sourcecountry:US sourcelang:eng',
                 'mode' => 'ArtList',
                 'maxrecords' => $max,
                 'format' => 'json',
                   'sort' => 'dateDesc',
                 'timespan' => '1hour',
             ],
         ]);

         $responseUk = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => 'sourcecountry:UK sourcelang:eng',
                 'mode' => 'ArtList',
                 'maxrecords' => $max,
                 'format' => 'json',
                 'sort' => 'dateDesc',
                 'timespan' => '1hour',
             ],
         ]);

         $responseNl = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => 'sourcecountry:NL sourcelang:NLD',
                 'mode' => 'ArtList',
                 'maxrecords' => $max,
                 'format' => 'json',
                 'sort' => 'dateDesc',
                 'timespan' => '1hour',
             ],
         ]);

         //adds the data and converts it to  array and merges the data
         $articlesUs = $responseUs->toArray()['articles'] ?? [];
         $articlesNl = $responseNl->toArray()['articles'] ?? [];
         $articlesUk = $responseUk->toArray()['articles'] ?? [];
         $mergedArticles = array_merge($articlesUs, $articlesUk, $articlesNl);

         return ['articles' => $mergedArticles];
      }
   }
