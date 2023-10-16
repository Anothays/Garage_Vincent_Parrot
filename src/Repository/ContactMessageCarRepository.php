<?php

namespace App\Repository;

use App\Entity\ContactMessageCar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactMessageCar>
 *
 * @method ContactMessageCar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMessageCar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMessageCar[]    findAll()
 * @method ContactMessageCar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMessageCarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMessageCar::class);
    }

//    /**
//     * @return ContactMessageCar[] Returns an array of ContactMessageCar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContactMessageCar
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
