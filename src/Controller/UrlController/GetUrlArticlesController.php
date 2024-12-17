<?php

   namespace App\Controller\UrlController;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
   use Symfony\Contracts\HttpClient\HttpClientInterface;

   class GetUrlArticlesController extends AbstractController
   {
      private HttpClientInterface $client;

      public function __construct(HttpClientInterface $client)
      {
         $this->client = $client;
      }


      /**
       * @throws TransportExceptionInterface
       * @throws ServerExceptionInterface
       * @throws RedirectionExceptionInterface
       * @throws DecodingExceptionInterface
       * @throws ClientExceptionInterface
       * @throws \Exception
       */
      public function fetchNewsUrl($max, $language): array
      {

         switch ($language) {
            case "unitedStates":
               $query = 'sourcecountry:US sourcelang:ENG';
               break;
            case "dutch":
               $query = 'sourcecountry:NL sourcelang:NLD';
               break;
            case "unitedKingdom":
               $query = 'sourcecountry:UK sourcelang:ENG';
               break;
            default:
               $query = '(sourcecountry:US OR sourcecountry:UK) sourcelang:eng';
         }

         // gets ulr from GDELT API for scrape
         $response = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => $query,
                 'mode' => 'ArtList',
                 'maxrecords' => $max,
                 'format' => 'json',
                 'sort' => 'DateDesc',
                 'timespan' => '1d',
             ],
         ]);
         // Controleer of de API-aanroep succesvol was
         if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from GDELT API. -Contact me.');
         }

         // Transformeer de API-respons naar een bruikbare array
         $data = $response->toArray();
         return $data['articles'] ?? [];
      }
   }