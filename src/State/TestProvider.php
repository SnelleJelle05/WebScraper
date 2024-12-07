<?php

   namespace App\State;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Message\ScrapeWebsiteMessage;
   use GuzzleHttp\Exception\GuzzleException;
   use Symfony\Component\Messenger\Exception\ExceptionInterface;
   use Symfony\Component\Messenger\MessageBusInterface;

   class TestProvider implements ProviderInterface
   {
      private MessageBusInterface $messageBus;

      public function __construct(MessageBusInterface $messageBus)
      {
         $this->messageBus = $messageBus;
      }

      /**
       * @throws GuzzleException
       * @throws \Exception
       * @throws ExceptionInterface
       */
      public function provide(Operation $operation, array $uriVariables = [], array $context = []): \Symfony\Component\Messenger\Envelope
      {
         $websiteUrls = [
             ["url" => 'http://www.newson6.com/story/64ddf94c57c6ce0730b84a06/weather-blog:-mild-weekend-ahead-rain-chances-increase-in-southeast-oklahoma'],
             ["url" => 'https://isp.netscape.com/news/world/story/0001/20241207/f9d74be4f3516eea499033aaf0f4f0d1'],
         ];

         return $this->messageBus->dispatch(new ScrapeWebsiteMessage($websiteUrls));

      }
   }
