<?php

   namespace App\Tests;

   class ScraperTest extends TestTemplate
   {
      public function testScraper(){
            $this->get('/api/news');
            self::assertResponseIsSuccessful();
      }
   }