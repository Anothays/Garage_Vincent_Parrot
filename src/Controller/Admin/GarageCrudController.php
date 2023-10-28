<?php

namespace App\Controller\Admin;

use App\Entity\Garage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GarageCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Garage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', "Nom de l'établissement"),
            TextField::new('email', "Email"),
            TextField::new('phoneNumber', "Téléphone"),
            TextField::new('address', "Adresse postale"),
            AssociationField::new('users', "Employés")->setFormTypeOption('by_reference', false),
            AssociationField::new('services', 'Prestations proposées')->setFormTypeOption('by_reference', false),
            AssociationField::new('cars', 'Voitures à vendre')->setFormTypeOption('by_reference', false),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn ($action) => $action->setLabel('Ajouter un établissement'))
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn ($action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, fn ($action) => $action->setLabel('Valider et continuer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, fn ($action) => $action->setLabel('Valider et créer un nouveau garage'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ;
    }
}
