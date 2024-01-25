<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, public EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Car::class);
    }

    public function findCarsPaginated(int $page, int $limit = 5): array
    {
        $result = [];
        $query = $this->createQueryBuilder('c')
            ->where('c.published = 1')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        if (empty($data)) {
            return $result;
        }

        // calcul du nombre de pages
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;
        $result['count'] = $paginator->count();
        return $result;
    }

    public function findByFilters($value): array
    {
        $result = [];
        $query = $this->createQueryBuilder('c')
            ->where('c.published = 1')
            ->andWhere('c.mileage >= :minMileage')
            ->andWhere('c.mileage <= :maxMileage')
            ->andWhere('c.price >= :minPrice')
            ->andWhere('c.price <= :maxPrice')
            ->andWhere('c.registrationDate BETWEEN :minYear AND :maxYear')
            ->setParameter('minMileage' ,$value["minMileage"])
            ->setParameter('maxMileage' ,$value["maxMileage"])
            ->setParameter('minPrice' ,$value["minPrice"])
            ->setParameter('maxPrice' ,$value["maxPrice"])
            ->setParameter('minYear' , $value['minYear'])
            ->setParameter('maxYear' , $value['maxYear'])
            ->orderBy('c.id', 'ASC')
            ->setMaxResults($value['selectPagination'])
            ->setFirstResult(($value['page'] * $value['selectPagination']) - $value['selectPagination']);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // calcul du nombre de pages
        $pages = ceil($paginator->count() / $value['selectPagination']);
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $value['page'];
        $result['limit'] = $value['selectPagination'];
        $result['count'] = $paginator->count();
        return $result;
    }

    public function getMinMaxValues() {
        return $this
            ->createQueryBuilder('m')
            ->select('MAX(m.mileage) as maxMileage, Min(m.mileage) as minMileage, MAX(m.price) as maxPrice, Min(m.price) as minPrice, MAX(m.registrationDate) as maxYear, MIN(m.registrationDate) as minYear')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
