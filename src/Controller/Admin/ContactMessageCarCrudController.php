<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessageCar;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ContactMessageCarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactMessageCar::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('contactMessage', 'Formulaire de contact concerné'),
            AssociationField::new('car', 'Véhicule concerné'),
        ];
    }

}
