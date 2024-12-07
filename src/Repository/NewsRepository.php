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
        public function SaveArticle($title,$description,$source,$imageUrl,$dateTime,$websiteUrl): void
        {
           $news = new News();
           $news->setTitle($title);
           $news->setDescription($description);
           $news->setSource($source);
           $news->setImageUrl($imageUrl);
           $news->setDate($dateTime);
           $news->setWebsiteUrl($websiteUrl);

           //save
           $this->getEntityManager()->persist($news);
           $this->getEntityManager()->flush();
        }

}
