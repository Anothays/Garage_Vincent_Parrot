<?php

namespace App\Controller\Admin;

use App\Entity\UserCustomer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
        yield EmailField::new('email', 'Email');
    }

}
