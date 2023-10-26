<?php

namespace App\Repository;

use App\Entity\UserStaffMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserStaffMember>
 *
 * @implements PasswordUpgraderInterface<UserStaffMember>
 *
 * @method UserStaffMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStaffMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStaffMember[]    findAll()
 * @method UserStaffMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStaffMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStaffMember::class);
    }

    public function save(UserStaffMember $user, bool $flush = false): void
    {
        $this->getEntityManager()->persist($user);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserStaffMember) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByRole(?string $role)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', '%' . $role . '%');
        return $query->getQuery()->getResult();
    }
}
