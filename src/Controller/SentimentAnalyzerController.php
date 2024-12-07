<?php

   namespace App\Controller;

   use Sentiment\Analyzer;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class SentimentAnalyzerController extends AbstractController
   {
      public function analyzerSentiment($data): ?string
      {
         try {
            $analyzer = new Analyzer();
            $output = $analyzer->getSentiment($data['title'] . ' ' . $data['description']);
            return number_format($output['compound'], 1);
         } catch (\Throwable $e) {
            dump($e->getMessage());
            return null;
         }
      }
   }
