<?php

namespace App\Repository;

use App\Entity\ImageCar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageCar>
 *
 * @method ImageCar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageCar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageCar[]    findAll()
 * @method ImageCar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageCarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageCar::class);
    }

//    /**
//     * @return ImageCar[] Returns an array of ImageCar objects
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

//    public function findOneBySomeField($value): ?ImageCar
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
