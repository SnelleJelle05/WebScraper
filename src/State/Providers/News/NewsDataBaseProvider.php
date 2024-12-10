<?php

   namespace App\State\Providers\News;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Repository\NewsRepository;

   class NewsDataBaseProvider implements ProviderInterface
   {

      public function __construct(private NewsRepository $newsRepository)
      {
      }

      public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
      {
         // TODO implement the apikey check
         dump($operation, $uriVariables, $context);
         dump($context['filters']);
         $data = $this->newsRepository->GetArticles();
         dump($data);
         return [$operation, $uriVariables, $data];
      }
   }
