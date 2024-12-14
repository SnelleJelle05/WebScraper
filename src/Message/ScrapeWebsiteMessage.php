<?php

namespace App\Message;

final readonly class ScrapeWebsiteMessage
{
   public function __construct(
       private array $websites
   ) {
   }

   public function getWebsites(): array
   {
      return $this->websites;
   }


}
