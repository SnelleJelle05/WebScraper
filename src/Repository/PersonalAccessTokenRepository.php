<?php

   namespace App\Repository;

   use App\Entity\PersonalAccessToken;
   use App\Entity\User;
   use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
   use Doctrine\Persistence\ManagerRegistry;

   /**
    * @extends ServiceEntityRepository<PersonalAccessToken>
    */
   class PersonalAccessTokenRepository extends ServiceEntityRepository
   {
      public function __construct(ManagerRegistry $registry)
      {
         parent::__construct($registry, PersonalAccessToken::class);
      }

      public function findUserByToken(string $pat): ?PersonalAccessToken
      {
         return $this->createQueryBuilder('p')
             ->innerJoin('p.relatedUser', 'r') // Join with the 'relatedUser' association
             ->select('p', 'r')  // Select both the root entity 'p' and the related 'r' entity
             ->andWhere('p.token = :token')
             ->setParameter('token', $pat)
             ->getQuery()
             ->getOneOrNullResult(); // Since you're expecting a single result, use getOneOrNullResult()
      }
   }

