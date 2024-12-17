<?php

   namespace App\Controller\UrlController;

   use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
   use Symfony\Contracts\HttpClient\HttpClientInterface;
   use Symfony\Contracts\HttpClient\ResponseInterface;

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
       * @throws \Exception
       */
      public function fetchNewsUrlSchedule(): array
      {
         $response = $this->request();
         // Controleer of de API-aanroep succesvol was
         if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from GDELT API. -Contact me.');
         }

         // Transformeer de API-respons naar een bruikbare array
         $data = $response->toArray();
         return $data['articles'] ?? [];
      }

      /**
       * @throws TransportExceptionInterface
       */
      private function request(): ResponseInterface
      {
         return $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => '(sourcecountry:US OR sourcecountry:UK OR sourcecountry:NL) (sourcelang:eng OR sourcelang:NLD)',
                 'mode' => 'ArtList',
                 'maxrecords' => 5,
                 'format' => 'json',
                 'timespan' => '1hour',
             ],
         ]);
      }
   }
