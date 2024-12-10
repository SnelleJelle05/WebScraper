<?php

   namespace App\Repository;

   use App\Entity\News;
   use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
   use Doctrine\Persistence\ManagerRegistry;


   class NewsRepository extends ServiceEntityRepository
   {
      public function __construct(ManagerRegistry $registry)
      {
         parent::__construct($registry, News::class);
      }

      /**
       * @return News[] Returns an array of News objects
       */
      public function SaveArticle($data): void
      {
         dump($data);
         try {
            $news = (new News())
                ->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setSource($data['source'])
                ->setImageUrl($data['imageUrl'])
                ->setDate($data['dateTime'])
                ->setWebsiteUrl($data['websiteUrl'])
                ->setSentiment($data['sentiment'])
                ->setLanguage($data['language'])
               ->setSourceCountry($data['sourcecountry'])
            ;
            $this->getEntityManager()->persist($news);
            $this->getEntityManager()->flush();
         } catch (\Throwable $e) {
            // Log the exception with context for easier debugging
            dump([
                'Database Error' => $e->getMessage(),
                'URL' => $data['websiteUrl'],
            ]);
         }
      }
      public function getArticles($max, $language, $sourceCountry): array
      {
         return $this->createQueryBuilder('n')
             ->select('n.title', 'n.description', 'n.source', 'n.imageUrl', 'n.date', 'n.websiteUrl', 'n.sentiment', 'n.language','n.sourceCountry')
             ->setMaxResults($max)
             ->andWhere('n.language = :language')
             ->setParameter('language', $language)
             ->andWhere('n.sourceCountry = :sourcecountry')
               ->setParameter('sourcecountry', $sourceCountry)
             ->getQuery()
             ->getResult();
      }
   }
