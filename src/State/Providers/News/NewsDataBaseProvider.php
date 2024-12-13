<?php

   namespace App\State\Providers\News;

   use ApiPlatform\Metadata\Operation;
   use ApiPlatform\State\ProviderInterface;
   use App\Repository\NewsRepository;
   use Symfony\ComponentHttpFoundation\Request;

   readonly class NewsDataBaseProvider implements ProviderInterface
   {

      public function __construct(private NewsRepository $newsRepository)
      {
      }

      public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
      {
         // TODO implement the apikey check
         if (!isset($context['filters'])) {
            throw new \RuntimeException('Filters are missing from the context.');
         }
         $articles = $this->newsRepository->getArticles($context['filters']['max'], $context['filters']['language'], $context['filters']['sourceCountry']);
         return [
             'status' => empty($articles) ? 204 : 200,
             'total' => count($articles),
             'data' => $articles,
         ];
      }
   }
