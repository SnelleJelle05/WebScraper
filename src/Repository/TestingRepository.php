<?php

namespace App\Repository;

use App\Entity\Testing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testing>
 */
class TestingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testing::class);
    }


        public function findOneBySomeField($value): ?Testing
        {
            return $this->createQueryBuilder('t')
                ->andWhere('t.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
}
