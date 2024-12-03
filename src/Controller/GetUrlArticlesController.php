<?php

   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\Routing\Attribute\Route;
   use Symfony\Contracts\HttpClient\HttpClientInterface;
   use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

   class GetUrlArticlesController extends AbstractController
   {
      private HttpClientInterface $client;

      public function __construct(HttpClientInterface $client)
      {
         $this->client = $client;
      }


      public function fetchNewsUrl($max): array
      {

         // Maak de API-aanroep met de hardcoded URL
         $response = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' =>'sourcecountry:NL sourcelang:nld',
                 'mode' => 'ArtList',
                 'maxrecords' => $max,
                 'format' => 'json',
             ],
         ]);

         dump($response);
         // Controleer of de API-aanroep succesvol was
         if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from GDELT API... Contact me.');
         }

         // Transformeer de API-respons naar een bruikbare array
         $data = $response->toArray();

         return $data['articles'] ?? [];
      }
   }