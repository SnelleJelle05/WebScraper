<?php

namespace App\MessageHandler;

use App\Message\RemoveOldArticlesMessage;
use App\Repository\NewsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RemoveOldArticlesMessageHandler
{
   public function __construct(private NewsRepository $newsRepository)
   {
   }

   public function __invoke(RemoveOldArticlesMessage $message): void
    {
        $this->newsRepository->deleteOldArticles();
    }
}
