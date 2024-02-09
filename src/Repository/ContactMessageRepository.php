<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\ContactMessage;
use App\Entity\ContactMessageCar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactMessage>
 *
 * @method ContactMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMessage[]    findAll()
 * @method ContactMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMessage::class);
    }

    public function save(ContactMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveAndUpdateAssociatedCar(ContactMessage $contactMessage, CarRepository | Car $carObject): void
    {
        if ($carObject instanceof CarRepository) {
            // Définition de la regex pour récupérer l'immatriculation
            $regex = '/[A-Z]{2}-\d{3}-[A-Z]{2}/';
            // Recherche de la correspondance dans la chaîne
            preg_match($regex, $contactMessage->getSubject(), $matches);
            // Vérification si une correspondance a été trouvée
            $immatriculation = $matches[0] ?? null;
            $associatedCar = $carObject
                ->createQueryBuilder('c')
                ->select('c')
                ->where('c.licensePlate = :val')
                ->setParameter('val', $immatriculation)
                ->getQuery()
                ->getOneOrNullResult();

            if (!empty($associatedCar)) {
                $contactMessageCar = new ContactMessageCar();
                $contactMessageCar->setCar($associatedCar);
            }
            $contactMessage->setConcernedCar($contactMessageCar);
            $this->save($contactMessage, true);
        } else if ($carObject instanceof Car) {
            $contactMessageCar = (new ContactMessageCar())->setCar($carObject)->setContactMessage($contactMessage);
            $this->getEntityManager()->persist($contactMessageCar);
            $this->getEntityManager()->persist($contactMessage);
            $this->getEntityManager()->flush();
        }
    }
}



