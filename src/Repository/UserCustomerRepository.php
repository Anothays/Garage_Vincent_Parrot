<?php

namespace App\Repository;

use App\Entity\UserCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserCustomer>
 *
 * @implements PasswordUpgraderInterface<UserCustomer>
 *
 * @method UserCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCustomer[]    findAll()
 * @method UserCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCustomer::class);
    }

    public function save(UserCustomer $user, bool $flush = false): void
    {
        $this->getEntityManager()->persist($user);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
