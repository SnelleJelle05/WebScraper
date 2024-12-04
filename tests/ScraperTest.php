<?php

   namespace App\Tests;

   class ScraperTest extends TestTemplate
   {
      public function testScraper()
      {
         $this->get('/api/news', ['max' => 3]);
         $json = $this->jsonResponse();
         var_dump($json);
         self::assertResponseIsSuccessful();
      }
   }