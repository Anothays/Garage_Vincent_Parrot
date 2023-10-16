<?php

namespace App\Repository;

use App\Entity\TestimonialApproved;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestimonialApproved>
 *
 * @method TestimonialApproved|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestimonialApproved|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestimonialApproved[]    findAll()
 * @method TestimonialApproved[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonialApprovedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestimonialApproved::class);
    }

//    /**
//     * @return TestimonialApproved[] Returns an array of TestimonialApproved objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TestimonialApproved
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
