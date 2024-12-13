<?php

namespace App\Scheduler;

use App\Controller\UrlController\FetchUrlScheduler;
use App\Controller\UrlController\GetUrlArticlesController;
use App\Message\ScrapeWebsiteMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsSchedule('GetArticlesOnSchedule')]
final class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache, private readonly FetchUrlScheduler $fetchUrlScheduler,
    ) {
    }

   /**
    * @throws TransportExceptionInterface
    * @throws ServerExceptionInterface
    * @throws RedirectionExceptionInterface
    * @throws DecodingExceptionInterface
    * @throws ClientExceptionInterface
    */
   public function getSchedule(): Schedule
    {
       $webSites = $this->fetchUrlScheduler->fetchNewsUrlSchedule();
        return (new Schedule())
            ->add(
                 RecurringMessage::every('1 hour', new ScrapeWebsiteMessage($webSites)),
            )
            ->stateful($this->cache)
        ;
    }
}
