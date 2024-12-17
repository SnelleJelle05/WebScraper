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

      public function CheckDoubles($title): int
      {
         $qb = $this->createQueryBuilder('n')
             ->select('COUNT(n.title) AS duplicateCount')
             ->where('n.title = :title')
             ->setParameter('title', $title);
         return $qb->getQuery()->getSingleScalarResult();
      }

      public function SaveArticle($data): void
      {
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
                ->setSourceCountry($data['sourcecountry']);
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
             ->select('n.title', 'n.description', 'n.source', 'n.imageUrl', 'n.date', 'n.websiteUrl', 'n.sentiment', 'n.language', 'n.sourceCountry')
             ->setMaxResults($max)
             ->andWhere('n.language = :language')
             ->setParameter('language', $language)
             ->andWhere('n.sourceCountry = :sourcecountry')
             ->setParameter('sourcecountry', $sourceCountry)
             ->getQuery()
             ->getResult();
      }

      public function deleteOldArticles(): void
      {
         $qb = $this->createQueryBuilder('n')
             ->delete()
             ->andWhere('n.date < :weekAgo')
             ->setParameter('weekAgo', (new \DateTime())->modify('-1 week'))
             ->getQuery();

         $result = $qb->execute(); // This returns the number of rows affected
         dump($result); // Dump the result to see the number of deleted rows
      }
   }
