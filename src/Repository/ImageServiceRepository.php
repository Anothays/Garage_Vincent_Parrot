<?php

namespace App\Repository;

use App\Entity\ImageService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageService>
 *
 * @method ImageService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageService[]    findAll()
 * @method ImageService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageService::class);
    }

//    /**
//     * @return ImageService[] Returns an array of ImageService objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageService
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
