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
         try {
            $news = (new News())
                ->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setSource($data['source'])
                ->setImageUrl($data['imageUrl'])
                ->setDate($data['dateTime'])
                ->setWebsiteUrl($data['websiteUrl'])
                ->setSentiment($data['sentiment'])
                ->setLanguage($data['language']);
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

      public function getArticles(): array
      {
         return $this->createQueryBuilder('n')
             ->select('n.title', 'n.description', 'n.source', 'n.imageUrl', 'n.date', 'n.websiteUrl', 'n.sentiment', 'n.language')
             ->setMaxResults(10)
             ->getQuery()
             ->getResult();
      }
      public function findOneBySomeField($value): ?News
      {
         return $this->createQueryBuilder('n')
             ->andWhere('n.exampleField = :val')
             ->setParameter('val', $value)
             ->getQuery()
             ->getOneOrNullResult()
             ;
      }

   }
