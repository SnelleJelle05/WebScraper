<?php

   namespace App\State\Providers\News;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Message\ScrapeWebsiteMessage;
   use Symfony\Component\Messenger\Exception\ExceptionInterface;
   use Symfony\Component\Messenger\MessageBusInterface;
   use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
   use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
   use Symfony\Contracts\HttpClient\HttpClientInterface;

   readonly class TestProvider implements ProviderInterface
   {

      public function __construct(
          private MessageBusInterface $messageBus,
          private HttpClientInterface $client
      )
      {
      }

      /**
       * @throws \Exception
       * @throws ExceptionInterface
       * @throws TransportExceptionInterface
       * @throws DecodingExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
      {
         $response = $this->client->request('GET', "https://api.gdeltproject.org/api/v2/doc/doc", [
             'query' => [
                 'query' => '(sourcecountry:US OR sourcecountry:UK OR sourcecountry:NL) (sourcelang:eng OR sourcelang:NLD)',
                 'mode' => 'ArtList',
                 'maxrecords' => 1,
                 'format' => 'json',
                 'sort' => 'ToneDesc',
                 'timespan' => '1 year',
             ],
         ]);
         $data = $response->toArray();
         $this->messageBus->dispatch(new ScrapeWebsiteMessage($data['articles']));

         return ['done'];

      }
   }
