<?php

namespace App\Controller\Admin;

use App\Entity\UserCustomer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class UsersCustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserCustomer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Client')
            ->setEntityLabelInPlural('Clients')
            ->setPageTitle('index','Liste des clients inscrits')
            ->showEntityActionsInlined()
            ->setFormOptions(
                ['validation_groups' => ['registration']],
                ['validation_groups' => ['update']],
            );
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('fullname', 'Nom complet');
        yield TextField::new('firstname', 'Prénom')->onlyOnDetail();
        yield TextField::new('lastname', 'Nom')->onlyOnDetail();
        yield EmailField::new('email', 'Email');
        yield BooleanField::new('isVerified', 'Compte vérifié')->hideOnForm()->renderAsSwitch(false);
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
//            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ;
    }
}
