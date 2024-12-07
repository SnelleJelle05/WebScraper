<?php

namespace App\Message;

final class ScrapeWebsiteMessage
{
   public function __construct(
       private int $max,
   ) {
   }

   public function getMax(): int
   {
      return $this->max;
   }
}
