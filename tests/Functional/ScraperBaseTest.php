<?php

   namespace App\Tests\Functional;

   use App\Tests\BaseTestCase;

   class ScraperBaseTest extends BaseTestCase
   {
      public function testScraper()
      {
         $this->get('/api/news', ['max' => 3]);
         $json = $this->jsonResponse();
         var_dump($json);
         self::assertResponseIsSuccessful();
      }
   }