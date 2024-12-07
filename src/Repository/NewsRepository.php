<?php

   namespace App\Repository;

   use App\Entity\News;
   use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
   use Doctrine\Persistence\ManagerRegistry;

   /**
    * @extends ServiceEntityRepository<News>
    */
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
         dumP($data);

         try {
            $news = (new News())
                ->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setSource($data['source'])
                ->setImageUrl($data['imageUrl'])
                ->setDate($data['dateTime'])
                ->setWebsiteUrl($data['websiteUrl']);

            $this->getEntityManager()->persist($news);
            $this->getEntityManager()->flush();
            dump($news);
         } catch (\Throwable $e) {
            // Log the exception with context for easier debugging
            dump([
                'Database Error' => $e->getMessage(),
                'URL' => $data['websiteUrl'],
            ]);
         }
      }
   }
