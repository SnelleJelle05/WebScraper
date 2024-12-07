<?php

   namespace App\Controller;

   use App\Entity\News;
   use Sentiment\Analyzer;
   use Stichoza\GoogleTranslate\GoogleTranslate;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

   class SentimentAnalyzerController extends AbstractController
   {
      public function analyzerSentiment($data): ?string
      {
         try {
            $analyzer = new Analyzer();
            $combinedText = $data['title'] . ' ' . $data['description'];

            if ($data !== 'English'){
               $translated = GoogleTranslate::trans($combinedText, 'en',  'auto');
               $output = $analyzer->getSentiment($translated);
               return number_format($output['compound'], 1);
            }

            $output = $analyzer->getSentiment($combinedText);
            return number_format($output['compound'], 1);
         } catch (\Throwable $e) {
            dump($e->getMessage());
            return null;
         }
      }
   }
