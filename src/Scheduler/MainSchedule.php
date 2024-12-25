<?php

namespace App\Scheduler;

use App\Controller\UrlController\FetchUrlSchedulerController;
use App\Message\RemoveOldArticlesMessage;
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

#[AsSchedule('MainSchedule')]
final readonly class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
        private FetchUrlSchedulerController $fetchUrlScheduler,
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
       try {
          $websites = $this->fetchUrlScheduler->fetchNewsUrlSchedule();
          if (empty($websites)) {
             throw new \RuntimeException('No websites found to schedule scraping.');
          }

          return (new Schedule())
              ->add(RecurringMessage::every('1 hour', new ScrapeWebsiteMessage($websites)))
              ->add(RecurringMessage::every('7 week', new RemoveOldArticlesMessage()))
              ->stateful($this->cache);

       } catch (\Exception $e) {
          dump($e);
          throw new \RuntimeException('Failed to create schedule.', 0, $e);
       }
    }
}
