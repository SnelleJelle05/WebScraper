<?php

namespace App\Message;

final class ScrapeWebsiteMessage
{
   public function __construct(
       private array $websites
   ) {
   }

   public function getwebsites(): array
   {
      return $this->websites;
   }


}
