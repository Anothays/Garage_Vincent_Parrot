<?php

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 *
 * @method Testimonial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testimonial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testimonial[]    findAll()
 * @method Testimonial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    public function save(Testimonial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTestimonialsPaginated(int $page, int $limit = 5): array
    {
        $result = [];
        $dql1 = "SELECT t.author, t.comment, t.note, t.createdAt FROM App\Entity\Testimonial as t WHERE t.approved = 1 ";
        $dql2 = "SELECT COUNT(t) as total FROM App\Entity\Testimonial t WHERE t.approved = 1";
        $query1 = $this->getEntityManager()->createQuery($dql1);
        $query2 = $this->getEntityManager()->createQuery($dql2);
        $query1->setMaxResults($limit);
        $query1->setFirstResult(($page - 1) * $limit);
        $comments = $query1->getResult();
        $total = $query2->getOneOrNullResult();
        if (empty($comments)) {
            return $result;
        }
        // calcul du nombre de pages
        $pages = ceil($total['total'] / $limit);
        // On remplit le tableau
        $result['data'] = $comments;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;
        $result['count'] = $total['total'];
        return $result;
    }


}
